<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $fillable = [
        'help','user_id','riddle_id','helped_by','helped_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function riddle()
    {
        return $this->belongsTo(Riddle::class);
    }

    public function helper()
    {
        return $this->belongsTo(User::class,'helped_by');
    }
}
