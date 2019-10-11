<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{
    protected $table = 'hints';
    protected $fillable = [
        'hint','number'
    ];

    public function riddle()
    {
        return $this->belongsTo(Riddle::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'user_hint','hint_id','user_id');
    }
}
