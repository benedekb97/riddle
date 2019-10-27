<?php

namespace App\Http\Controllers;

use App\Models\Guess;
use App\Models\Riddle;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function user(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = User::where('api_key',$request->input('api_key'))->first();
        if($user==null){
            abort(403);
        }

        return response()->json($user);
    }

    public function nextRiddle(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = User::where('api_key',$request->input('api_key'))->first();
        if($user==null){
            abort(403);
        }

        $next_riddle = $user->unlockNextRiddle();

        if($next_riddle == null) {
          return response()->json(['success' => false]);
        } else {
          return response()->json(['success' => true]);
        }
    }

    public function riddle(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = User::where('api_key',$request->input('api_key'))->first();
        if($user==null){
            abort(403);
        }

        $riddle = $user->current_riddle();
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

        return response()->json($response);
    }

    public function checkRiddle(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = User::where('api_key',$request->input('api_key'))->first();
        if($user==null){
            abort(403);
        }

        $riddle = $user->current_riddle();

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
            if(time()-$last_guess_time>2){
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
            return response()->json(['success' => true]);

        }else{
            return response()->json(['success' => false]);
        }

    }

    public function home(Request $request)
    {
        $text = Setting::all()->where('name','mobile_text')->first();

        $text = $text->setting;

        return response()->json(['home_text' => $text]);
    }

    public function previous(Request $request)
    {
        if($request->input('api_key')==null){
            abort(403);
        }

        $user = User::where('api_key',$request->input('api_key'))->first();

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

        return response()->json($return);
    }

    public function getRiddle(Request $request, Riddle $riddle, $api_key)
    {


        if($api_key==null){
            abort(403);
        }

        $user = User::where('api_key', $api_key)->first();
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
        $user = User::where('api_key', $request->input('api_key'))->first();
        if($user==null){
            abort(403);
        }

        if($user->current_riddle()->hints()->count()-$user->usedHints($user->current_riddle())->count()>0){
            return response()->json(['has_hints' => true]);
        }else{
            return response()->json(['has_hints' => false]);
        }
    }
}
