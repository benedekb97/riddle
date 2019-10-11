<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riddle extends Model
{
    protected $table = 'riddles';
    protected $fillable = [
        'title', 'image','difficulty','answer','approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hints()
    {
        return $this->hasMany(Hint::class);
    }

    public function solvedBy()
    {
        return $this->belongsToMany(User::class,'user_riddle','user_id','riddle_id');
    }

    public function isUnsolved()
    {
        if($this->solvedBy == null) {
            return true;
        }else{
            return false;
        }
    }

    public function guesses()
    {
        return $this->hasMany(Guess::class);
    }
}
