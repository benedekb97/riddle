<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riddle extends Model
{
    protected $table = 'riddles';
    protected $fillable = [
        'title', 'image','difficulty','answer','approved','number','blocked','approved_by','approved_at','blocked_by','blocked_at','block_reason'
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
        return $this->belongsToMany(User::class,'user_riddle','riddle_id','user_id');
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

    public function blocker()
    {
        return $this->belongsTo(User::class,'blocked_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function duplicates()
    {
        return $this->hasMany(Duplicate::class,'riddle_id','id');
    }
}
