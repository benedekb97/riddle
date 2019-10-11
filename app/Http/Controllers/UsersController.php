<?php

namespace App\Http\Controllers;

use App\Models\Riddle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function list()
    {
        $users = User::all()->sortByDesc('points');

        return view('users.list', [
            'users' => $users
        ]);
    }

    public function profile()
    {
        return view('users.profile');
    }

    public function riddles(Riddle $riddle = null, $option = null)
    {
        $riddles = Auth::user()->riddles;

        return view('users.riddles', [
            'riddles' => $riddles,
            'view_hints' => $riddle,
            'option' => $option
        ]);
    }

    public function edit()
    {
        $user = Auth::user();

        return view('users.edit');
    }

    public function save(Request $request)
    {
        Auth::user()->nickname = $request->input('nickname');
        Auth::user()->save();

        return redirect(route('users.profile'));
    }
}
