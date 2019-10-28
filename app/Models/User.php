<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Str;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','internal_id','points','moderator','approved','nickname','admin','blocked','api_key','given_names','surname'
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
        return $this->activeRiddles()->orderBy('number', 'desc')->first();
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

    /**
     * @return Riddle $riddle
     */
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

    public function getPoints()
    {
        $score = 0;

        $solved_riddles = $this->solvedRiddles()->get();
        foreach($solved_riddles as $riddle){

            $used_hints = $this->usedHints($riddle)->count();

            $add_to_score = $riddle->difficulty*(max(1,5-$used_hints));


            if($riddle->user_id == $this->id){
                $add_to_score = 0;
            }elseif($riddle->approved_by == $this->id){
                $add_to_score = 0;
            }
            if($this->helpsAsked()->where('riddle_id',$riddle->id)->where('help','!=',null)->count()>0){
                $add_to_score = 0;
            }

            $score += $add_to_score;

        }

        $uploaded_riddles = $this->riddles()->get();
        foreach($uploaded_riddles as $riddle){
            if($riddle->approved==true)
                $score += 15;
        }

        return $score;
    }

    public function generateNewApiKey()
    {
        $new_key = new ApiKey();
        $new_key->key = Str::random(60);
        $new_key->user_id = $this->id;
        $new_key->valid = date('Y-m-d H:i:s', time()+60*60*24);
        $new_key->ip = $_SERVER['REMOTE_ADDR'];
        $new_key->save();

        return $new_key;
    }

    public function guessesCount(Riddle $riddle)
    {
        $guesses = $this->guesses()->where('riddle_id',$riddle->id)->get();

        $count = 0;

        foreach($guesses as $guess){
            $count += $guess->count;
        }

        return $count;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }
}
