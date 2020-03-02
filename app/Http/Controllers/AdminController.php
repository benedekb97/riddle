<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\LogCategory;
use App\Models\Riddle;
use App\Models\StaticMessage;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\VarDumper\Cloner\Data;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        Log::create('admin.page.view','','admin.index', Auth::user());

        return view('admin.index');
    }

    public function staticMessages()
    {
        Log::create('admin.page.view','','admin.static_messages',Auth::user());

        $messages = StaticMessage::all()->where('active','1')->sortBy('number');
        $message_types = ['info','danger','warning','success','primary','default'];
        $message_icons = ['fa-info-circle','fa-exclamation-triangle','fa-exclamation-circle','fa-check','fa-info-circle','fa-info-circle'];

        return view('admin.static_messages',[
            'messages' => $messages,
            'message_types' => $message_types,
            'message_icons' => $message_icons
        ]);
    }

    public function moveStaticMessageUp(StaticMessage $message)
    {
        if($message->number==StaticMessage::all()->min('number')){
            abort(403);
        }

        $message_before = StaticMessage::where('number','<',$message->number)->get()->sortBy('number')->last();
        $number = $message->number;
        $number_before = $message_before->number;
        $message_before->number = $number;
        $message->number = $number_before;
        $message->save();
        $message_before->save();

        return redirect()->back();
    }

    public function moveStaticMessageDown(StaticMessage $message)
    {
        if($message->number==StaticMessage::all()->max('number')){
            abort(403);
        }

        $message_before = StaticMessage::where('number','>',$message->number)->get()->sortBy('number')->first();
        $number = $message->number;
        $number_before = $message_before->number;
        $message_before->number = $number;
        $message->number = $number_before;
        $message->save();
        $message_before->save();

        return redirect()->back();
    }

    public function deleteStaticMessage(StaticMessage $message, Request $request)
    {
        Log::create('delete.static_message',$message->id,'admin.static_messages.delete',Auth::user());

        $id = $message->id;
        StaticMessage::all()->find($id)->first()->delete();

        return redirect()->back();
    }

    public function editStaticMessage(StaticMessage $message, Request $request)
    {
        Log::create('edit.static_message', $message->id, 'admin.static_messages.edit', Auth::user());

        $id = $message->id;
        $message->title = $request->input('title'.$id);
        $message->message = $request->input('message'.$id);
        $message->type = $request->input('type'.$id);
        $message->save();

        return redirect()->back();
    }

    public function newStaticMessage(Request $request)
    {
        $message = new StaticMessage();
        $message->title = $request->input('title');
        $message->message = $request->input('message');
        $message->type = $request->input('type');
        $message->active = 1;
        $message->save();

        Log::create('new.static_message', $message->id,'admin.static_messages.new',Auth::user());

        return redirect()->back();
    }

    public function moderators()
    {
        Log::create('admin.page.view','','admin.moderators',Auth::user());

        $moderators = User::all()->where('moderator',true)->all();

        return view('admin.moderators', [
            'moderators' => $moderators
        ]);
    }

    public function search(Request $request)
    {
        $search_name = $request->input('search');
        $users = User::where('name','like',"%$search_name%")->where('moderator',0)->get();
        $users_array = [];
        foreach($users as $user){
            $users_array[] = [
                'name' => $user->name,
                'id' => $user->id
            ];
        }

        return response()->json($users_array);
    }

    public function addModerator(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $user->moderator = 1;
        $user->save();

        Log::create('new.moderator',$user->id,'admin.moderators.new',Auth::user());

        return redirect()->back();
    }

    public function deleteModerator(User $user)
    {
        Log::create('delete.moderator',$user->id,'admin.moderators.delete',Auth::user());

        $user->moderator = 0;
        $user->save();

        return redirect()->back();
    }

    public function functions()
    {
        Log::create('admin.page.view','','admin.functions',Auth::user());

        $lockdown = Setting::where('name','lockdown')->where('setting','true')->count()>0;

        return view('admin.functions',[
            'lockdown' => $lockdown
        ]);
    }

    public function enableLockdown()
    {
        Log::create('lockdown','enable','admin.functions.lockdown.enable',Auth::user());

        $setting = Setting::where('name','lockdown')->first();
        $setting->setting = "true";
        $setting->save();

        return redirect()->back();
    }

    public function disableLockdown()
    {
        Log::create('lockdown','disable','admin.functions.lockdown.disable',Auth::user());

        $setting = Setting::where('name','lockdown')->first();
        $setting->setting = "false";
        $setting->save();

        return redirect()->back();
    }

    public function resetCurrentRiddles()
    {
        Log::create('reset_riddles','','admin.functions.reset_riddles',Auth::user());

        $users = User::all();
        foreach($users as $user)
        {
            $user->unlockNextRiddle();
        }

        return redirect()->back();
    }

    public function users()
    {
        Log::create('admin.page.view','','admin.users',Auth::user());

        $users = User::all();

        return view('admin.users', [
            'users' => $users
        ]);
    }

    public function userData(DataTables $datatables)
    {
        $query = User::select(['*']);

        return $datatables->eloquent($query)
            ->addColumn('solved_riddles', function(User $user){
                return $user->solvedRiddles()->count();
            })
            ->addColumn('riddles', function(User $user){
                return $user->riddles()->count();
            })
            ->addColumn('moderator', function(User $user){
                if($user->moderator==true){
                    return "check";
                }else{
                    return "times";
                }
            })
            ->addColumn('admin', function(User $user){
                if($user->admin==true){
                    return "check";
                }else{
                    return "times";
                }
            })
            ->addColumn('internal_id', function(User $user){
                if($user->internal_id!=null){
                    return "check";
                }else{
                    return "times";
                }
            })
            ->addColumn('password', function(User $user){
                if($user->password!=null){
                    return "check";
                }else{
                    return "times";
                }
            })
            ->addColumn('blocked_riddles', function(User $user){
                return $user->blockedRiddles();
            })
            ->addColumn('avg_diff', function(User $user){
                return $user->riddles()->average('difficulty');
            })
            ->addColumn('block', function(User $user){
                return $user->blocked==true;
            })
            ->make();
    }

//    public function deleteUser(User $user){
//        $user->delete();
//
//        return redirect()->back();
//    }

    public function blockUser(User $user)
    {
        Log::create('user.block',$user->id,'admin.users.block',Auth::user());

        $user->blocked = true;
        $user->save();

        return redirect()->back();
    }

    public function unblockUser(User $user)
    {
        Log::create('user.unblock',$user->id,'admin.users.unblock',Auth::user());

        $user->blocked = false;
        $user->save();

        return redirect()->back();
    }

    public function logs(Request $request)
    {
        Log::create('admin.page.view','','admin.logs',Auth::user());

        $api = LogCategory::find(1);
        $admin = LogCategory::find(2);
        $moderator = LogCategory::find(3);
        $user = LogCategory::find(4);

        return view('admin.logs', [
            'api' => $api,
            'admin' => $admin,
            'moderator' => $moderator,
            'user' => $user
        ]);
    }

    public function logsApi(Request $request)
    {
        return view('admin.logs.api');
    }

    public function logsUser(Request $request)
    {
        return view('admin.logs.user');
    }

    public function logsAdmin(Request $request)
    {
        return view('admin.logs.admin');
    }

    public function logsModerator(Request $request)
    {
        return view('admin.logs.moderator');
    }

    protected function getData(DataTables $datatables, $query)
    {
        return $datatables->eloquent($query)
            ->addColumn('user_id', function(Log $log){
                if($log->user!=null){
                    return $log->user->name;
                }else{
                    return "";
                }
            })
            ->addColumn('created_at_lol', function(Log $log){
                return $log->created_at;
            })
            ->addColumn('description', function(Log $log){
                return $log->getType->description;
            })
            ->addColumn('page', function(Log $log){
                return $log->page;
            })
            ->addColumn('riddle_id', function(Log $log){
                if($log->riddle!=null){
                    return $log->riddle->title;
                }else{
                    return "";
                }
            })
            ->make();
    }

    public function logsApiData(DataTables $datatables)
    {
        $query = LogCategory::find(1)->logs()->select(['logs.*','log_types.description'])->orderByDesc('logs.created_at');

        return $this->getData($datatables, $query);
    }

    public function logsAdminData(DataTables $dataTables)
    {
        $query = LogCategory::find(2)->logs()->select(['logs.*','log_types.description'])->orderByDesc('logs.created_at');

        return $this->getData($dataTables,$query);
    }

    public function logsModeratorData(DataTables $dataTables)
    {
        $query = LogCategory::find(3)->logs()->select(['logs.*', 'log_types.description'])->orderByDesc('logs.created_at');

        return $this->getData($dataTables,$query);
    }

    public function logsUserData(DataTables $dataTables)
    {
        $query = LogCategory::find(4)->logs()->select(['logs.*', 'log_types.description'])->orderByDesc('logs.created_at');

        return $this->getData($dataTables, $query);
    }

    public function logData(DataTables $datatables)
    {
        $query = Log::select(array('*'))->orderBy('id','desc');

        return $datatables->eloquent($query)
            ->addColumn('user_id', function(Log $log){
                if($log->user != null){
                    return $log->user->name;
                }else{
                    return "";
                }
            })
            ->addColumn('type', function(Log $log){
                return $log->getType->description;
            })
            ->addColumn('riddle_id', function(Log $log){
                if($log->riddle != null){
                    return $log->riddle->id;
                }else{
                    return "";
                }
            })
            ->make(true);
    }

    public function api()
    {
        Log::create('admin.page.view','','admin.api',Auth::user());

        return view('admin.api');
    }

    public function newApiTokens()
    {
        Log::create('generate.api.tokens','','admin.api',Auth::user());

        $users = User::all();
        foreach($users as $user){
            $keys = $user->apiKeys()->get();
            foreach($keys as $key){
                $key->delete();
            }
        }

        return redirect()->back();
    }

    public function deleteInvalidApiKeys()
    {
        Log::create('delete.api.tokens','','admin.api',Auth::user());
        $users = User::all();
        foreach($users as $user){
            $keys = $user->apiKeys()->get();
            foreach($keys as $key){
                if(!$key->isValid()){
                    $key->delete();
                }
            }
        }

        return redirect()->back();
    }

    public function riddles()
    {
        Log::create('admin.page.view','','admin.riddles',Auth::user());

        return view('admin.riddles');
    }

    public function riddleData(DataTables $datatables)
    {
        $query = Riddle::select(['*']);

        return $datatables->eloquent($query)
            ->addColumn('hint',function(Riddle $riddle){
                return $riddle->hints->toArray();
            })
            ->addColumn('user_id', function(Riddle $riddle){
                if($riddle->user!=null){
                    return $riddle->user->name;
                }else{
                    return "";
                }
            })
            ->addColumn('image', function(Riddle $riddle) {
                return $riddle->id;
            })
            ->addColumn('blocked',function(Riddle $riddle){
                if($riddle->blocked==false){
                    return "times";
                }else{
                    return "check";
                }
            })
            ->addColumn('approved',function(Riddle $riddle){
                if($riddle->approved==false){
                    return "times";
                }else{
                    return "check";
                }
            })
            ->addColumn('approved_by', function(Riddle $riddle){
                if($riddle->approver!=null){
                    return $riddle->approver->name;
                }else{
                    if($riddle->approved==true){
                        return $riddle->user->name;
                    }else{
                        return "";
                    }
                }
            })
            ->addColumn('blocked_by', function(Riddle $riddle){
                if($riddle->blocker!=null){
                    return $riddle->blocker->name;
                }else{
                    return "";
                }
            })
            ->addColumn('solved', function(Riddle $riddle){
                return $riddle->solvedBy()->count();
            })
            ->make(true);
    }


    public function setPoints()
    {
        $users = User::all();
        foreach($users as $user){
            $user->points = $user->getPoints();
            $user->save();
        }

        return redirect()->back();
    }
}
