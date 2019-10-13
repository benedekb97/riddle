<?php

namespace App\Http\Controllers;

use App\Models\Riddle;
use App\Models\StaticMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if(!Auth::check())
        {
            return redirect(route('login'));
        }

        $messages = StaticMessage::all();
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
        if($riddle->approved==0 && (Auth::user()->id != $riddle->user_id || !Auth::user()->moderator)) {
            abort(403);
        }

        if($riddle->approved && Auth::user()->id == $riddle->user_id && !Auth::user()->moderator) {
            abort(403);
        }

        if((Auth::user()->current_riddle != $riddle->id) && (!Auth::user()->moderator)) {
            abort(403);
        }

        if(Auth::user()->solvedRiddles()->find($riddle->id) != null){
            $solved = true;
        }else{
            $solved = false;
        }

        $approved = $riddle->approved;

        $hints = Auth::user()->usedHints($riddle)->get();

        $point_multiplier = max(1,5-$hints->count());

        $points = $riddle->difficulty * $point_multiplier;

        $solved_riddles = Auth::user()->solvedRiddles;

        $reported = Auth::user()->duplicates()->where('duplicate_id',$riddle->id)->count() != 0;

        $difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

        return view('riddle', [
            'riddle' => $riddle,
            'solved' => $solved,
            'hints' => $hints,
            'approved' => $approved,
            'points' => $points,
            'difficulties' => $difficulties,
            'solved_riddles' => $solved_riddles,
            'reported' => $reported
        ]);
    }

    public function current()
    {
        if(Auth::user()->current_riddle == null) {
            return redirect(route('riddles.next'));
        }else{
            return redirect(route('riddle', ['riddle' => Auth::user()->current_riddle]));
        }

    }

    public function noneLeft()
    {
        return view('noneleft');
    }
}
