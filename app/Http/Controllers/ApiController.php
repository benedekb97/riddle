<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Guess;
use App\Models\Log;
use App\Models\Riddle;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function user(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));
        if($user==null){
            Log::create('api.request','fail:api_key','api.user');
            abort(403);
        }

        Log::create('api.request',$user->name,'api.user',$user);
        return response()->json($user);
    }

    public function nextRiddle(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));
        if($user==null){
            Log::create('api.request','fail:api_key','api.nextRiddle');
            abort(403);
        }

        if($user->current_riddle()==null){
            $next_riddle = $user->unlockNextRiddle();
        }else{
            Log::create('api.request','fail:current_riddle','api.nextRiddle',$user);
            return response()->json(['success' => false]);
        }


        if($next_riddle == null) {
          Log::create('api.request','fail:no_new_riddle','api.nextRiddle',$user);
          return response()->json(['success' => false]);
        } else {
          Log::create('api.request','success','api.nextRiddle',$user);
          return response()->json(['success' => true]);
        }
    }

    public function riddle(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));
        if($user==null){
            Log::create('api.request','fail:api_key','api.riddle');
            abort(403);
        }

        $riddle = $user->current_riddle();
        if($riddle==null){
            Log::create('api.request', 'fail:no_riddle','api.riddle',$user);
            return response()->json(['riddle' => null]);
        }else{
            $difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

            $riddle_difficulty = $difficulties[$riddle->difficulty-1];
            $hints = $user->usedHints($riddle)->get();

            $send_hints = [];

            foreach($hints as $hint){
                $send_hints[] = $hint->hint;
            }

            $unused_hints = $riddle->hints_count - $hints->count();

            $response = [
                'id' => $riddle->id,
                'title' => $riddle->title,
                'creator' => $riddle->user->name,
                'difficulty' => $riddle_difficulty,
                'hints' => $send_hints,
                'image' => route('api.get.riddle', ['riddle' => $riddle, 'api_key' => $user->api_key]),
                'unused_hints' => $unused_hints
            ];

            Log::create('api.request','success','api.riddle',$user,$riddle);
            return response()->json($response);
        }
    }

    public function checkRiddle(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }
        if($request->input('answer')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));
        if($user==null){
            Log::create('api.request','fail:api_key','api.check');
            abort(403);
        }

        $riddle = $user->current_riddle();

        if($riddle==null){
            Log::create('api.request','fail:no_riddle','api.check',$user);
            return response()->json(['success' => false]);
        }

        $last_guess = $riddle->guesses()->where('user_id',$user->id)->max('updated_at');

        $answer_given = $request->input('answer');

        $same_guess = $user->guesses()->where('riddle_id',$riddle->id)->where('guess',$answer_given)->first();

        if($same_guess!=null){
            $same_guess->count++;
            $same_guess->updated_at = date('Y-m-d H:i:s');
            $same_guess->save();
        }else{
            $guess = new Guess();
            $guess->count = 1;
            $guess->guess = Riddle::normalise($answer_given);
            $guess->user_id = $user->id;
            $guess->riddle_id = $riddle->id;
            $guess->save();
        }

        if($last_guess!=null){
            $last_guess_time = strtotime($last_guess);
            if(time()-$last_guess_time<2){
                Log::create('api.request','fail:timeout','api.check',$user);
                abort(403);
            }
        }

        $point_multiplier = max(1,5-$user->usedHints($riddle)->count());

        if($riddle->check($answer_given)){
            $user->solvedRiddles()->attach($riddle);
            $user->solvedRiddles()->find($riddle->id)->first()->setUpdatedAt(time());
            $user->solvedRiddles()->find($riddle->id)->first()->setCreatedAt(time());
            $help = $riddle->helps()->where('user_id',$user->id)->where('help','!=',null)->first();

            if($help!=null){
                $points = 0;
            }else{
                $points = $riddle->difficulty*$point_multiplier;
            }

            if($riddle->helps()->where('user_id',$user->id)->where('help',null)->count()>0){
                $help = $riddle->helps()->where('user_id',$user->id)->where('help',null)->first();
                $help->delete();
            }

            $user->activeRiddles()->detach($riddle);
            $user->points = $user->getPoints();
            $user->save();
            Log::create('api.request','success','api.check', $user, $riddle);
            return response()->json(['success' => true]);

        }else{
            Log::create('api.request','fail:wrong_answer','api.check', $user, $riddle);
            return response()->json(['success' => false]);
        }

    }

    public function home(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));
        if($user==null){
            Log::create('api.request','fail:api_key','api.home');
            abort(403);
        }

        Log::create('api.request','success','api.home',$user);
        $text = Setting::all()->where('name','mobile_text')->first();

        $text = $text->setting;

        return response()->json(['home_text' => $text]);
    }

    public function previous(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));

        if($user==null){
            Log::create('api.request','fail:api_key','api.previous');
            abort(403);
        }

        $return = [];

        $riddles = $user->solvedRiddles;
        foreach($riddles as $riddle){
            $return[] = [
                'title' => $riddle->title,
                'answer' => $riddle->answer,
                'image' => route('api.get.riddle', ['riddle' => $riddle, 'api_key' => $user->api_key]),
                'solved_at' => $user->solvedRiddles()->where('riddle_id',$riddle->id)->first()->created_at,
                'tries' => $user->guessesCount($riddle),
                'used_hints' => $user->usedHints($riddle)->count(),
                'difficulty' => $riddle->difficulty
            ];
        }

        Log::create('api.request','success','api.previous',$user);
        return response()->json($return);
    }

    public function getRiddle(Riddle $riddle, $api_key)
    {
        if($api_key==null){
            abort(403);
        }
        
        dd($api_key);

        $user = ApiKey::getUser($api_key);
        if($user==null){
            abort(403);
        }

        if($user->solvedRiddles->contains($riddle) || $user->moderator || $user->activeRiddles->contains($riddle->id) || $user->riddles->contains($riddle)){
            $path = $riddle->image;
            return response()->file(storage_path("app/" . $path));
        }else{
            return abort(403);
        }
    }

    public function hasHintsLeft(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));
        if($user==null){
            Log::create('api.request','fail:api_key','api.hasHintsLeft');
            abort(403);
        }

        if($user->current_riddle()==null){
            Log::create('api.request','fail:no_riddle','api.hasHintsLeft',$user);
            return response()->json(['has_hints' => false]);
        }

        if($user->current_riddle()->hints()->count()-$user->usedHints($user->current_riddle())->count()>0){
            Log::create('api.request','success:true','api.hasHintsLeft',$user,$user->current_riddle());
            return response()->json(['has_hints' => true]);
        }else{
            Log::create('api.request','success:false','api.hasHintsLeft',$user,$user->current_riddle());
            return response()->json(['has_hints' => false]);
        }
    }


    public function scores(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }
        $user = ApiKey::getUser($request->input('api_key'));

        if($user==null){
            Log::create('api.request','fail:api_key','api.scores');
            abort(403);
        }

        $scores = [];
        $users = User::all()->sortByDesc('points');

        $i = 0;

        foreach($users as $user_loop){
            $i++;
            $scores[] = [
                'rank' => $i,
                'name' => $user_loop->name,
                'points' => $user_loop->points,
                'riddles' => $user_loop->solvedRiddles()->count(),
                'uploaded_riddles' => $user_loop->approvedRiddles()->count()
            ];
        }

        Log::create('api.request','success','api.scores',$user);
        return $scores;

    }

    public function nextHint(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = ApiKey::getUser($request->input('api_key'));
        if($user==null){
            Log::create('api.request','fail:api_key','api.nextHint');
            abort(403);
        }

        $riddle = $user->current_riddle();
        if($riddle==null){
            Log::create('api.request','fail:no_riddle','api.nextHint',$user);
            return response()->json(['success' => false]);
        }
        $used_hints_count = $user->usedHints($riddle)->count();
        $total_hints_count = $riddle->hints()->count();

        if($user->usedHints($riddle) == null){
            $next_hint = $riddle->hints()->where('number',1)->first();
        }else{
            $next_hint = $riddle->hints()->where('number', $user->usedHints($riddle)->max('number')+1)->first();
        }

        if($total_hints_count>$used_hints_count){
            $user->hints()->attach($next_hint->id);
            $user->save();
            Log::create('api.request','success','api.nextHint',$user,$riddle);
            return response()->json(['hint' => $next_hint->hint]);
        }else{
            Log::create('api.request','success:no_hints','api.nextHint',$user,$riddle);
            return response()->json(['success' => false]);
        }

    }

    public function description()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        if(Auth::user()->apiKeys()->where('valid','>',date("Y-m-d H:i:s"))->count()==0){
            $api_key = Auth::user()->generateNewApiKey();
        }else{
            $api_key = Auth::user()->apiKeys()->where('valid','>',date("Y-m-d H:i:s"))->first();
        }

        Log::create('page.view','','api.description',Auth::user());
        return view('api_description', ['api_key' => $api_key->key]);
    }
}
