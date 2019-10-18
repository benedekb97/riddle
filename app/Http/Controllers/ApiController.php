<?php

namespace App\Http\Controllers;

use App\Models\Riddle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function user()
    {
        $user = Auth::user();

        return response()->json($user);
    }

    public function nextRiddle()
    {
        if(Auth::user()->riddle == null){
            $riddles = Riddle::all();
            $solvedRiddles = Auth::user()->solvedRiddles()->get();
            $unapproved_riddles = Riddle::all()->where('approved','0');
            $blocked_riddles = Riddle::all()->where('blocked','1');
            $unsequenced_riddles = Riddle::all()->where('number',null);
            $unsolved_riddles = $riddles->diff($solvedRiddles);
            $unsolved_riddles = $unsolved_riddles->diff($unapproved_riddles);
            $unsolved_riddles = $unsolved_riddles->diff($blocked_riddles);
            $unsolved_riddles = $unsolved_riddles->diff($unsequenced_riddles);
            if($unsolved_riddles->count() != 0) {

                $unsolved_riddles = $unsolved_riddles->sortBy('number');
                $next_riddle = $unsolved_riddles->first();

                Auth::user()->current_riddle = $next_riddle->id;
                Auth::user()->save();

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function riddle()
    {
        $riddle = Auth::user()->riddle;
        $difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

        $riddle_difficulty = $difficulties[$riddle->difficulty];
        $hints = Auth::user()->usedHints($riddle)->get();

        $unused_hints = $riddle->hints_count - $hints->count();

        $response = [
            'id' => $riddle->id,
            'title' => $riddle->title,
            'creator' => $riddle->user->name,
            'difficulty' => $riddle_difficulty,
            'hints' => $hints,
            'unused_hints' => $unused_hints
        ];

        return response()->json($response);
    }

    public function checkRiddle(Request $request)
    {
        $riddle = Riddle::find($request->input('riddle_id'));
    }
}
