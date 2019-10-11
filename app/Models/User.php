<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','internal_id','points','moderator','approved'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function riddles()
    {
        return $this->hasMany(Riddle::class);
    }

    public function solvedRiddles()
    {
        return $this->belongsToMany(Riddle::class, 'user_riddle','riddle_id','user_id');
    }

    public function unsolvedRiddles()
    {
        $riddles = $this->riddles->count();

        return $riddles;
    }

    public function guesses()
    {
        return $this->hasMany(Guess::class);
    }

    public function riddle()
    {
        return $this->belongsTo(Riddle::class,'current_riddle');
    }
}
