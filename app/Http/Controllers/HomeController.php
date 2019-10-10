<?php

namespace App\Http\Controllers;

use App\Models\Riddle;
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

        return view('index');
    }

    public function team()
    {
        $user = Auth::user();
        if($user == null || $user->team_id == null) {
            return redirect(route('index'));
        }

        $team = $user->team;

        return view('team', [
            'team' => $team
        ]);
    }

    public function riddle(Riddle $riddle)
    {
        return view('riddle', [
            'riddle' => $riddle
        ]);
    }
}
