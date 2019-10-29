<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'type','user_id','data','page','riddle_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function riddle()
    {
        return $this->belongsTo(Riddle::class);
    }

    public static function create($type, $data, $page,User $user=null,Riddle $riddle=null)
    {
        $log = new Log();
        $log->type = $type;
        $log->data = $data;
        $log->page = $page;
        $log->ip = $_SERVER['REMOTE_ADDR'];
        if($user!=null){
            $log->user_id = $user->id;
        }else{
            $log->user_id = null;
        }
        if($riddle!=null){
            $log->riddle_id = $riddle->id;
        }else{
            $log->riddle_id = null;
        }
        $log->save();
    }

    public function getType()
    {
        return $this->belongsTo(LogType::class,'type','name','log_types');
    }
}
