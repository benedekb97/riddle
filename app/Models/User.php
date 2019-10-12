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
        'name', 'email', 'password','internal_id','points','moderator','approved','nickname','admin'
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
        return $this->belongsToMany(Riddle::class, 'user_riddle','user_id','riddle_id');
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

    public function usedHints(Riddle $riddle)
    {
        return $this->belongsToMany(Hint::class,'user_hint','user_id','hint_id')->where('riddle_id',$riddle->id);
    }

    public function hints()
    {
        return $this->belongsToMany(Hint::class,'user_hint','user_id','hint_id');
    }

    public function duplicates()
    {
        return $this->hasMany(Duplicate::class);
    }

    public function unapprovedRiddles()
    {
        return $this->riddles->where('approved','0')->where('blocked','0')->count();
    }

    public function blockedRiddles()
    {
        return $this->riddles->where('blocked','1')->count();
    }
}
