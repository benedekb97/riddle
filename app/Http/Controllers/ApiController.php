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
        $next_riddle = Auth::user()->unlockNextRiddle();

        if($next_riddle == null) {
          return response()->json(['success' => false]);
        } else {
          return response()->json(['success' => true]);
        }
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
