<?php

namespace App\Http\Controllers;

use App\Models\Riddle;
use App\Models\StaticMessage;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function staticMessages()
    {
        $messages = StaticMessage::all()->where('active','1');
        $message_types = ['info','danger','warning','success','primary','default'];
        $message_icons = ['fa-info-circle','fa-exclamation-triangle','fa-exclamation-circle','fa-check','fa-info-circle','fa-info-circle'];

        return view('admin.static_messages',[
            'messages' => $messages,
            'message_types' => $message_types,
            'message_icons' => $message_icons
        ]);
    }

    public function deleteStaticMessage(StaticMessage $message, Request $request)
    {
        $id = $message->id;
        StaticMessage::all()->find($id)->first()->delete();

        return redirect()->back();
    }

    public function editStaticMessage(StaticMessage $message, Request $request)
    {
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

        return redirect()->back();
    }

    public function moderators()
    {
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

        return redirect()->back();
    }

    public function deleteModerator(User $user)
    {
        $user->moderator = 0;
        $user->save();

        return redirect()->back();
    }

    public function functions()
    {
        return view('admin.functions');
    }

    public function resetCurrentRiddles()
    {
        $users = User::all();
        foreach($users as $user)
        {
            $riddles = Riddle::all();
            $solvedRiddles = $user->solvedRiddles()->get();
            $unapproved_riddles = Riddle::all()->where('approved','0');
            $blocked_riddles = Riddle::all()->where('blocked','1');
            $unsequenced_riddles = Riddle::all()->where('number',null);
            $unsolved_riddles = $riddles->diff($solvedRiddles);
            $unsolved_riddles = $unsolved_riddles->diff($unapproved_riddles);
            $unsolved_riddles = $unsolved_riddles->diff($blocked_riddles);
            $unsolved_riddles = $unsolved_riddles->diff($unsequenced_riddles);

            if($unsolved_riddles->count()!=0){
                $unsolved_riddles = $unsolved_riddles->sortBy('number');
                $next_riddle = $unsolved_riddles->first();

                $user->current_riddle = $next_riddle->id;
                $user->save();
            }
        }

        return redirect()->back();
    }
}
