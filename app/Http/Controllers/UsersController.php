<?php

namespace App\Http\Controllers;

use App\Models\Guess;
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
        $guesses = Guess::all()->groupBy('riddle_id');

        return view('users.riddles', [
            'riddles' => $riddles,
            'view_hints' => $riddle,
            'option' => $option,
            'guesses' => $guesses
        ]);
    }

    public function edit($error = null)
    {
        $user = Auth::user();

        $error_messages = [
            1 => 'Nem egyeznek a megadott jelszavak!',
            2 => 'A jelszónak minimim 8 karakter hosszúnak kell lennie'
        ];

        if($error != null) {
            $error_message = $error_messages[$error];
        }else{
            $error_message = null;
        }

        return view('users.edit',[
            'error_message' => $error_message
        ]);
    }

    public function save(Request $request)
    {
        Auth::user()->nickname = $request->input('nickname');

        $password = $request->input('password');
        $password2 = $request->input('password2');

        if($password!='') {
            if($password!=$password2) {
                return redirect(route('users.profile.edit',['error' => 1]));
            }
            if(strlen($password)<8) {
                return redirect(route('users.profile.edit', ['error' => 2]));
            }
            Auth::user()->password = bcrypt($password);
        }

        Auth::user()->save();

        return redirect(route('users.profile'));
    }

    public function creators()
    {
        $users = User::all();

        return view('users.creators', [
            'users' => $users
        ]);
    }

    public function modify(User $user)
    {
        $user->approved = !$user->approved;
        $user->save();

        return redirect()->back();
    }
}
