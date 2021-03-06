<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = [
        'key','user_id','valid','ip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        $valid = strtotime($this->valid);
        return time()<$valid;
    }

    /**
     * @param $key
     * @return User $user
     */
    public static function getUser($key)
    {
        $key = ApiKey::all()->where('key',$key)->first();

        if($key==null){
            return null;
        }

        $valid = strtotime($key->valid);
        if(time()<$valid){
            $key->valid = date("Y-m-d H:i:s",time()+60*60);
            $key->save();

            return $key->user;
        }else{
            $key->delete();
            return null;
        }
    }
}
