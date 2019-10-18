<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function user()
    {
        $user = Auth::user();

        return response()->json($user);
    }

    public function riddle()
    {
        $riddle = Auth::user()->riddle;

        $riddle_title = $riddle->title;
        $hints = Auth::user()->hints->where('riddle_id',$riddle->id);

        return response()->json($riddle);
    }

    public function checkRiddle(Request $request)
    {

    }
}
