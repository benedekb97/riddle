<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogType extends Model
{
    protected $fillable = [
        'description','name'
    ];

    public function logs()
    {
        return $this->hasMany(Log::class,'type','name');
    }

    public function category()
    {
        return $this->belongsTo(LogCategory::class);
    }
}
