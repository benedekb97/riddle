<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticMessage extends Model
{
    protected $table = 'static_messages';

    protected $fillable = [
        'type','message','active'
    ];
}
