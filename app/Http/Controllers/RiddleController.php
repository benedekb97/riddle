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
            $answer = strtolower($request->input('answer'));
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


            return redirect(route('users.riddles'));
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
        $riddles = Riddle::all()->where('approved','0')->where('blocked','0')->all();

        return view('riddles.unapproved', [
            'riddles' => $riddles
        ]);
    }

    public function blocked()
    {
        $riddles = Riddle::all()->where('blocked','1')->all();

        return view('riddles.blocked', [
            'riddles' => $riddles
        ]);
    }

    public function check(Riddle $riddle, Request $request)
    {

        if(Auth::user() == $riddle->user) {
            abort(403);
        }
        if(Auth::user()->current_riddle != $riddle->id) {
            abort(403);
        }
        if($riddle->approved!=1 && Auth::user()->moderator!=1){
            abort(403);
        }
        $last_guess = $riddle->guesses()->where('user_id',Auth::user()->id)->max('updated_at');

        $answer_given = $request->input('answer');


        if($last_guess !=null){

            $last_guess_time =  strtotime($last_guess);
            if(time()-$last_guess_time<2){
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

        if(str_replace(' ','',strtolower($answer_given)) == str_replace(' ','',strtolower($riddle->answer))) {
            Auth::user()->solvedRiddles()->attach($riddle);
            Auth::user()->solvedRiddles()->find($riddle->id)->first()->setUpdatedAt(time());
            Auth::user()->solvedRiddles()->find($riddle->id)->first()->setCreatedAt(time());
            Auth::user()->current_riddle = null;
            if($riddle->user_id != Auth::user()->id){
                Auth::user()->points += $riddle->difficulty*$point_multiplier;
            }
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

    public function approve(Riddle $riddle, $return = null)
    {
        if(Auth::user()->moderator!=1)
        {
            abort(403);
        }

        $riddle->approved = 1;
        $riddle->approved_by = Auth::user()->id;
        $riddle->approved_at = date("Y-m-d H:i:s");
        $riddle->blocked = 0;
        $riddle->blocked_by = null;
        $riddle->blocked_at = null;
        $riddle->block_reason = null;
        $riddle->save();

        if($return == 'mod') {
            return redirect(route('riddles.unapproved'));
        }elseif($return == 'blocked') {
            return redirect(route('riddles.blocked'));
        }else{
            return redirect(route('riddle',['riddle' => $riddle]));
        }

    }

    public function block(Riddle $riddle, Request $request, $return = null)
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

        if($return == 'mod') {
            return redirect(route('riddles.unapproved'));
        }else{
            return redirect(route('riddle',['riddle' => $riddle]));
        }

    }

    public function edit(Riddle $riddle, Request $request)
    {
        if(Auth::user()->moderator!=1 && Auth::user()->id != $riddle->user_id)
        {
            abort(403);
        }

        $riddle->title = $request->input('title'.$riddle->id);
        $riddle->answer = str_replace(' ','',strtolower($request->input('answer'.$riddle->id)));
        $riddle->difficulty = $request->input('difficulty'.$riddle->id);
        $riddle->blocked = false;
        $riddle->blocked_by = null;
        $riddle->blocked_at = null;
        $riddle->block_reason = null;
        if(!Auth::user()->moderator){
            $riddle->approved = false;
            $riddle->approved_by = null;
            $riddle->approved_at = null;
        }
        $riddle->save();

        return redirect(route('users.riddles'));

    }

    public function next()
    {
        if(Auth::user()->current_riddle != null) {
            return redirect(route('riddles.current'));
        }

        $riddles = Riddle::all();
        $solvedRiddles = Auth::user()->solvedRiddles()->get();
        $unapproved_riddles = Riddle::all()->where('approved','0');
        $blocked_riddles = Riddle::all()->where('blocked','1');
        $unsequenced_riddles = Riddle::all()->where('number',null);
        $unsolved_riddles = $riddles->diff($solvedRiddles);
        $unsolved_riddles = $unsolved_riddles->diff($unapproved_riddles);
        $unsolved_riddles = $unsolved_riddles->diff($blocked_riddles);
        $unsolved_riddles = $unsolved_riddles->diff($unsequenced_riddles);
        if($unsolved_riddles->count() != 0){

            $unsolved_riddles = $unsolved_riddles->sortBy('number');
            $next_riddle = $unsolved_riddles->first();

            Auth::user()->current_riddle = $next_riddle->id;
            Auth::user()->save();

            return redirect(route('riddles.current'));
        }else{
            return redirect(route('riddles.noneleft'));
        }

    }

    public function all()
    {
        $riddles = Auth::user()->solvedRiddles()->get();
        $difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

        return view('riddles.all', ['riddles' => $riddles, 'difficulties' => $difficulties]);
    }

    public function sequence()
    {
        $sequenced_riddles = Riddle::all()->where('number','!=',null)->sortByDesc('number');
        $unsequenced_riddles = Riddle::all()->diff($sequenced_riddles)->where('approved','1')->where('blocked','0');
        $last_number = Riddle::all()->max('number');

        return view('riddles.sequence', [
            'sequenced_riddles' => $sequenced_riddles,
            'unsequenced_riddles' => $unsequenced_riddles,
            'last_number' => $last_number
        ]);
    }

    public function addToSequence(Riddle $riddle)
    {
        $last_number = Riddle::all()->max('number');

        $riddle->number = $last_number + 1;
        $riddle->save();

        return redirect()->back();
    }

    public function sequenceUp(Riddle $riddle)
    {
        $next_riddle = Riddle::where('number',$riddle->number+1)->first();
        $next_number = $riddle->number+1;
        $next_riddle->number = null;
        $next_riddle->save();
        $riddle->number = $next_number;
        $riddle->save();
        $next_riddle->number = $next_number-1;
        $next_riddle->save();

        return redirect()->back();
    }

    public function sequenceDown(Riddle $riddle)
    {
        $prev_riddle = Riddle::where('number',$riddle->number-1)->first();
        $prev_number = $riddle->number-1;
        $prev_riddle->number = Null;
        $prev_riddle->save();
        $riddle->number = $prev_number;
        $riddle->save();
        $prev_riddle->number = $prev_number+1;
        $prev_riddle->save();

        return redirect()->back();
    }
}
