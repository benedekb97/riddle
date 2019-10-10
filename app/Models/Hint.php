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

}
