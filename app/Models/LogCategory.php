<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogCategory extends Model
{
    protected $fillable = [
        'name','description'
    ];

    public function types()
    {
        return $this->hasMany(LogType::class);
    }

    public function logs()
    {
        return $this->hasManyThrough(Log::class,LogType::class,'log_category_id','type','id','name');
    }
}
