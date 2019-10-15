<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riddle extends Model
{
    protected $table = 'riddles';
    protected $fillable = [
        'title', 'image','difficulty','answer','approved','number','blocked','approved_by','approved_at','blocked_by','blocked_at','block_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hints()
    {
        return $this->hasMany(Hint::class);
    }

    public function solvedBy()
    {
        return $this->belongsToMany(User::class,'user_riddle','riddle_id','user_id');
    }

    public function isUnsolved()
    {
        if($this->solvedBy == null) {
            return true;
        }else{
            return false;
        }
    }

    public function guesses()
    {
        return $this->hasMany(Guess::class);
    }

    public function blocker()
    {
        return $this->belongsTo(User::class,'blocked_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function duplicates()
    {
        return $this->hasMany(Duplicate::class,'riddle_id','id');
    }

    public static function normalise(string $text)
    {
        $text = strtolower($text);
        $replace = [
            'this' => [
                'á','é','í','ó','ú','ő','ö','ű','ü','Á','É','Í','Ő','Ó','Ö','Ű','Ú','Ü',' '
            ],
            'with' => [
                'a','e','i','o','u','o','o','u','u','a','e','i','o','o','o','u','u','u',''
            ]
        ];
        foreach($replace['this'] as $key => $letter){
            $text = str_replace($replace['this'][$key],$replace['with'][$key],$text);
        }

        return $text;
    }

    public function check(string $answer)
    {

        return $this->normalise($answer)==$this->normalise($this->answer);
    }

    public function compare(Riddle $riddle)
    {
        $answer1 = $this->answer;
        $answer2 = $riddle->answer;


        $answer1 = strtolower($answer1);
        $answer1 = str_replace(' ','',$answer1);
        $answer1 = str_replace('é','e',$answer1);
        $answer1 = str_replace('á','a',$answer1);
        $answer1 = str_replace('í','i',$answer1);
        $answer1 = str_replace('ó','o',$answer1);
        $answer1 = str_replace('ő','o',$answer1);
        $answer1 = str_replace('ö','o',$answer1);
        $answer1 = str_replace('ú','u',$answer1);
        $answer1 = str_replace('ü','u',$answer1);
        $answer1 = str_replace('ű','u',$answer1);

        $answer2 = strtolower($answer2);
        $answer2 = str_replace(' ','',$answer2);
        $answer2 = str_replace('é','e',$answer2);
        $answer2 = str_replace('á','a',$answer2);
        $answer2 = str_replace('í','i',$answer2);
        $answer2 = str_replace('ó','o',$answer2);
        $answer2 = str_replace('ő','o',$answer2);
        $answer2 = str_replace('ö','o',$answer2);
        $answer2 = str_replace('ú','u',$answer2);
        $answer2 = str_replace('ü','u',$answer2);
        $answer2 = str_replace('ű','u',$answer2);

        return $answer1 == $answer2;
    }

    public function helps()
    {
        return $this->hasMany(Help::class);
    }
}
