<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Riddle;
use App\Models\StaticMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        Log::create('page.view','','index',Auth::user());

        if(!Auth::check())
        {
            return redirect(route('login'));
        }

        $messages = StaticMessage::all()->sortBy('number');
        $message_types = ['info','danger','warning','success','primary','default'];
        $message_icons = ['fa-info-circle','fa-exclamation-triangle','fa-exclamation-circle','fa-check','fa-info-circle','fa-info-circle'];

        return view('index',[
            'messages' => $messages,
            'message_types' => $message_types,
            'message_icons' => $message_icons
        ]);
    }

    public function login($error = null)
    {
        Log::create('page.view','','index',Auth::user());

        if(Auth::check()){
            abort(403);
        }

        $error_codes = [
            1 => 'A megadott jelszavak nem egyeznek!',
            2 => 'Ez az email már szerepel az adatbázisban!',
            3 => 'A jelszavadnak minimum 8 karakter hosszúnak kell lennie!'
        ];

        if($error != null)
        {
            $error_message = $error_codes[$error];
        }else{
            $error_message = null;
        }

        return view('login', [
            'error' => $error,
            'error_message' => $error_message
        ]);
    }

    public function riddle(Riddle $riddle)
    {
        $user = Auth::user();
        Log::create('page.view','','riddle',$user,$riddle);

        if($riddle->approved==0 && ($user->id != $riddle->user_id || !$user->moderator)) {
            abort(403);
        }

        if((!$user->activeRiddles->contains($riddle->id)) && (!$user->moderator)) {
            abort(403);
        }

        if($user->solvedRiddles()->find($riddle->id) != null){
            $solved = true;
        }else{
            $solved = false;
        }

        $approved = $riddle->approved;

        $hints = $user->usedHints($riddle)->get();

        $point_multiplier = max(1,5-$hints->count());

        $points = $riddle->difficulty * $point_multiplier;

        $solved_riddles = $user->solvedRiddles;

        $solved_by = $riddle->solvedBy()->first();

        $reported = $user->duplicates()->where('duplicate_id',$riddle->id)->count() != 0;

        $difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

        $helped = $riddle->helps()->where('user_id',$user->id)->count() > 0;
        $help = $riddle->helps()->where('user_id',$user->id)->first();
        if($help!=null && $help->help!=null){
            $help->seen = true;
            $help->save();
        }

        return view('riddle', [
            'riddle' => $riddle,
            'solved' => $solved,
            'hints' => $hints,
            'approved' => $approved,
            'points' => $points,
            'difficulties' => $difficulties,
            'solved_riddles' => $solved_riddles,
            'solved_by' => $solved_by,
            'reported' => $reported,
            'helped' => $helped,
            'help' => $help,
            'show_skip' => $user->activeRiddles()->get()->every(function($riddle) use ($user){
                return $riddle->guesses()->where('user_id', $user->id)->count() >= 5;
            }) && $user->activeRiddles()->count() < 5,
            'show_help' => $user->activeRiddles()->get()->every(function($riddle) use ($user){
                return $riddle->guesses()->where('user_id', $user->id)->count() >= 15;
            }) && $user->activeRiddles()->count() == 5 && $helped==false
        ]);
    }

    public function current()
    {
        Log::create('page.view','','current',Auth::user());

        if(Auth::user()->activeRiddles()->count() == 0) {
            return redirect(route('riddles.next'));
        } else {
            $current_riddle = Auth::user()->activeRiddles()->orderBy('number', 'desc')->first();
            return redirect(route('riddle', ['riddle' => $current_riddle]));
        }

    }

    public function noneLeft()
    {
        Log::create('page.view','','none_left', Auth::user());

        return view('noneleft', [
          'activeRiddles' => Auth::user()->activeRiddles()->count()
        ]);
    }
}
