<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaticMessage extends Model
{
    use SoftDeletes;

    protected $table = 'static_messages';

    protected $fillable = [
        'type','message','active','number'
    ];
}
