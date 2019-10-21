<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','internal_id','points','moderator','approved','nickname','admin','blocked','api_key'
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


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function riddles()
    {
        return $this->hasMany(Riddle::class);
    }

    public function solvedRiddles()
    {
        return $this->belongsToMany(Riddle::class, 'user_riddle','user_id','riddle_id');
    }

    public function activeRiddles()
    {
      return $this->belongsToMany(Riddle::class, 'active_riddles', 'user_id', 'riddle_id');
    }

    public function unlockNextRiddle()
    {
      if ($this->activeRiddles()->count() >= 5) {
        return $this->activeRiddles()->first();
      }

      $next_riddle = Riddle::query()
        ->where('approved', '1')
        ->where('blocked', '0')
        ->whereNotNull('number')
        ->get()
        ->diff($this->solvedRiddles)
        ->diff($this->activeRiddles)
        ->sortBy('number')
        ->first();

      if($next_riddle == null) {
        return null;
      } else {
        $this->activeRiddles()->save($next_riddle);
        return $next_riddle;
      }
    }

    public function current_riddle()
    {
      return $this->activeRiddles()->orderBy('number', 'desc')->first();
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

    public function helpsAsked()
    {
        return $this->hasMany(Help::class,'user_id');
    }

    public function helpsAnswered()
    {
        return $this->hasMany(Help::class,'helped_by');
    }

    public function approvedRiddles()
    {
        return $this->riddles()->where('approved','1')->where('blocked','0');
    }

    public function riddlesApprovedBy()
    {
      return Riddle::all()->where('approved_by', $this->id);
    }

    public function riddlesBlockedBy()
    {
        return Riddle::all()->where('blocked_by',$this->id);
    }

    public function myHelps()
    {
        if($this->moderator==1){
            $this_helps = Help::all()->where('help',null);
        }else{
            $this_helps = [];
            $riddles = $this->riddles;
            foreach($riddles as $riddle){
                $helps = $riddle->helps;
                foreach($helps as $help){
                    if($help->help==null){
                        $this_helps[] = $help;
                    }
                }
            }
            $this_helps = $this->newCollection($this_helps);
        }

        return $this_helps;
    }
}
