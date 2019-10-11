<?php

namespace App\Http\Controllers;

use App\Models\Guess;
use App\Models\Hint;
use App\Models\Riddle;
use Faker\Provider\Image;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RiddleController extends Controller
{

    private $errors = [
        1 => [
            'name' => 'filesize',
            'message' => 'A feltölthető fájl maximális mérete 5MB!'
        ],
        2 => [
            'name' => 'extension',
            'message' => 'Csak jp(e)g, png és gif típusú fájlokat tölthetsz fel :\'('
        ],
        3 => [
            'name' => 'extsize',
            'message' => "Max 5MB-os jp(e)g, png és gif fájlot tölthetel fel..."
        ],
        4 => [
            'name' => 'title',
            'message' => 'Nem írtál be címet a ridülnek'
        ],
        5 => [
            'name' => 'titlesize',
            'message' => 'Nincs cím és túl nagy a ridülöd'
        ],
        6 => [
            'name' => 'exttitle',
            'message' => 'jp(e)g, png, gif legyen, és adj neki címet pls'
        ],
        7 => [
            'name' => 'all',
            'message' => 'Konkrétan mindent elbasztál tesó... maximum 5MB-os lehet a fájl, aminek jp(e)g, gif, vagy png kiterjesztésűnek kell lennie, és adjál már egy címet a gyökér riddle-ödnek barátom.'
        ],
        8 => [
            'name' => 'file',
            'message' => 'Válassz ki egy fájlt a gépedről :P'
        ]
    ];

    private $allowed_extensions = [
        'jpg','jpeg','png','gif'
    ];

    public function new($error = null)
    {

        if(Auth::user()->unsolvedRiddles()>4)
        {
            return redirect(route('index', ['error' => 1]));
        }

        if(!Auth::user()->approved){
            $approved=false;
        }else{
            $approved=true;
        }

        if($error != null){
            return view('riddles.new', [
                'error_message' => $this->errors[$error]['message'],
                'approved' => $approved
            ]);
        }else{
            return view('riddles.new', [
                'approved' => $approved
            ]);
        }

    }

    public function save(Request $request)
    {

        /** @var  UploadedFile $file */
        if($request->file('riddle')) {
            $time = time();

            $file = $request->file('riddle');
            $size = $file->getSize()/1024/1024;
            $extension = strtolower($file->extension());

            $error = 0;

            if($size>5){
                $error += 1;
            }

            if(!in_array($extension,$this->allowed_extensions)){
                $error += 2;
            }

            if($request->input('title')==""){
                $error +=4;
            }

            if($error>0){
                return redirect(route('riddles.new', ['error' => $error]));
            }

            $file_name = 'images/' . $file->getFilename() . "." . $time . "." . $file->extension();

            Storage::disk()->put($file_name, File::get($file));

            $title = $request->input('title');
            $answer = $request->input('answer');
            $difficulty = $request->input('difficulty');

            if($difficulty>5 || $difficulty<1) {
                $difficulty = 3;
            }

            $riddle = new Riddle();
            $riddle->title = $title;
            $riddle->image = $file_name;
            $riddle->user_id = Auth::id();
            $riddle->answer = $answer;
            $riddle->difficulty = $difficulty;
            $riddle->approved = Auth::user()->approved || Auth::user()->moderator;

            $riddle->save();


            return redirect(route('riddle', [
                'riddle' => $riddle
            ]));
        }else{
            return redirect(Route('riddles.new', ['error' => 8]));
        }
    }

    public function get(Riddle $riddle)
    {
        $path = $riddle->image;
        return response()->file(storage_path("app/" . $path));
    }

    public function unapproved()
    {
        return view('riddles.unapproved');
    }

    public function check(Riddle $riddle, Request $request)
    {

        if(Auth::user() == $riddle->user) {
//            abort(403);
        }
        if(Auth::user()->current_riddle != $riddle) {
//            abort(403);
        }
        if($riddle->approved!=1 && Auth::user()->moderator!=1){
//            abort(403);
        }
        $last_guess = $riddle->guesses()->where('user_id',Auth::user()->id)->max('updated_at');

        $answer_given = $request->input('answer');


        if($last_guess !=null){

            $last_guess_time =  strtotime($last_guess);
            if(time()-$last_guess_time<4){
                abort(403);
            }

        }

        $same_guess = Auth::user()->guesses()->where('riddle_id',$riddle->id)->where('guess',$answer_given)->first();

        if($same_guess!=null)
        {
            $same_guess->count++;
            $same_guess->updated_at = date("Y-m-d H:i:s",time());
            $same_guess->save();
        }else{
            $guess = new Guess();
            $guess->guess = $answer_given;
            $guess->user_id = Auth::user()->id;
            $guess->riddle_id = $riddle->id;
            $guess->save();
        }


        $point_multiplier = max(1,5-Auth::user()->usedHints($riddle)->count());

        if(strtolower($answer_given) == strtolower($riddle->answer)) {
            Auth::user()->solvedRiddles()->attach($riddle);
            Auth::user()->solvedRiddles()->find($riddle->id)->first()->setUpdatedAt(time());
            Auth::user()->solvedRiddles()->find($riddle->id)->first()->setCreatedAt(time());
            Auth::user()->points += $riddle->difficulty*$point_multiplier;
            Auth::user()->save();
            return response()->json(['guess' => 'correct']);
        }else{
            return response()->json(['guess' => 'wrong']);
        }
    }

    public function hint(Riddle $riddle)
    {
        $user = Auth::user();

        if($user->usedHints($riddle) == null) {
            $next_hint = $riddle->hints()->where('number',1)->first();
        }else{
            $next_hint = $riddle->hints()->where('number',$user->usedHints($riddle)->max('number')+1)->first();
        }

        if($user->usedHints($riddle)->count() < $riddle->hints()->count())
        {
            $user->hints()->attach($next_hint->id);
            $user->save();
        }else{
            abort(403);
        }

        return redirect(route('riddle', ['riddle' => $riddle]));
    }

    public function deleteHint(Riddle $riddle, Hint $hint)
    {
        if($riddle->user == Auth::user())
        {
            $hint->delete();
        }else{
            abort(403);
        }

        return redirect(route('users.riddles', ['riddle' => $riddle, 'option' => 'hint']));
    }

    public function addHint(Riddle $riddle, Request $request)
    {
        $hint_number = $riddle->hints()->count()+1;

        if($riddle->user == Auth::user())
        {
            $hint = new Hint();
            $hint->riddle_id = $riddle->id;
            $hint->hint = $request->input('hint');
            $hint->number = $hint_number;
            $hint->save();
        }else{
            abort(403);
        }

        return redirect(route('users.riddles', ['riddle' => $riddle, 'option' => 'hint']));
    }

    public function approve(Riddle $riddle)
    {
        if(Auth::user()->moderator!=1)
        {
            abort(403);
        }

        $riddle->approved = 1;
        $riddle->approved_by = Auth::user()->id;
        $riddle->approved_at = date("Y-m-d H:i:s");
        $riddle->save();

        return redirect(route('riddle',['riddle' => $riddle]));
    }

    public function block(Riddle $riddle, Request $request)
    {
        if(Auth::user()->moderator!=1)
        {
            abort(403);
        }

        $riddle->blocked = 1;
        $riddle->blocked_by = Auth::user()->id;
        $riddle->blocked_at = date("Y-m-d H:i:s");
        $riddle->block_reason = $request->input('reason');
        $riddle->save();

        return redirect(route('riddle',['riddle' => $riddle]));
    }
}
