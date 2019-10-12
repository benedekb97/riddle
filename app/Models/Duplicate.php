<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duplicate extends Model
{
    protected $table = "duplicates";

    protected $fillable = [
        'user_id','original_id','duplicate_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function riddle()
    {
        return $this->belongsTo(Riddle::class,'riddle_id');
    }

    public function duplicate()
    {
        return $this->belongsTo(Riddle::class,'duplicate_id');
    }
}
