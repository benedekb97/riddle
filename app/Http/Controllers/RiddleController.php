<?php

namespace App\Http\Controllers;

use App\Models\Duplicate;
use App\Models\Guess;
use App\Models\Help;
use App\Models\Hint;
use App\Models\Log;
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
        Log::create('page.view','','riddles.new',Auth::user());

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
                Log::create('new.riddle.attempt',$error,'riddles.save',Auth::user());

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
            Auth::user()->points += 15*(Auth::user()->approved || Auth::user()->moderator);

            $riddle->save();

            Log::create('new.riddle','','riddles.save',Auth::user(),$riddle);

            return redirect(route('users.riddles'));
        }else{
            Log::create('new.riddle',8,'riddles.save',Auth::user());

            return redirect(Route('riddles.new', ['error' => 8]));
        }
    }

    public function get(Riddle $riddle)
    {
        if(Auth::user()->solvedRiddles->contains($riddle) || Auth::user()->moderator || Auth::user()->activeRiddles->contains($riddle->id) || Auth::user()->riddles->contains($riddle)){
            $path = $riddle->image;
            return response()->file(storage_path("app/" . $path));
        }else{
            abort(403);
        }
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
        if(!Auth::user()->activeRiddles->contains($riddle) && !Auth::user()->moderator) {
            abort(403);
        }
        if($riddle->approved!=1 && Auth::user()->moderator!=1){
            abort(403);
        }
        $last_guess = $riddle->guesses()->where('user_id',Auth::user()->id)->max('updated_at');

        $answer_given = $request->input('answer');



        $same_guess = Auth::user()->guesses()->where('riddle_id',$riddle->id)->where('guess',$answer_given)->first();

        if($same_guess!=null)
        {
            $same_guess->count++;
            $same_guess->updated_at = date("Y-m-d H:i:s",time());
            $same_guess->save();

            Log::create('guess.riddle',$same_guess->guess,'riddles.check',Auth::user(),$riddle);
        }else{
            $guess = new Guess();
            $guess->guess = Riddle::normalise($answer_given);
            $guess->user_id = Auth::user()->id;
            $guess->riddle_id = $riddle->id;
            $guess->count = 1;
            $guess->save();
            Log::create('guess.riddle',$guess->guess,'riddles.check',Auth::user(),$riddle);
        }


        if($last_guess !=null){

            $last_guess_time =  strtotime($last_guess);
            if(time()-$last_guess_time<2){
                Log::create('guess.riddle.fail','timeout','riddles.check',Auth::user(),$riddle);

                abort(403);
            }

        }

        $point_multiplier = max(1,5-Auth::user()->usedHints($riddle)->count());

        if($riddle->check($answer_given)) {
            Auth::user()->solvedRiddles()->attach($riddle);
            Auth::user()->solvedRiddles()->find($riddle->id)->first()->setUpdatedAt(time());
            Auth::user()->solvedRiddles()->find($riddle->id)->first()->setCreatedAt(time());
            $help = $riddle->helps()->where('user_id',Auth::user()->id)->where('help','!=',null)->first();
            if($help!=null){
                $points = 0;
            }else{
                $points = $riddle->difficulty*$point_multiplier;
            }
            if($riddle->helps()->where('user_id',Auth::user()->id)->where('help',null)->count()>0){
                $help = $riddle->helps()->where('user_id',Auth::user()->id)->where('help',null)->first();
                $help->delete();
            }

            Auth::user()->activeRiddles()->detach($riddle);
            if($riddle->user_id != Auth::user()->id){
                Auth::user()->points = Auth::user()->getPoints();
            }
            Auth::user()->save();

            Log::create('riddle.solve','','riddles.check',Auth::user(),$riddle);
            return response()->json(['guess' => 'correct']);
        }else{
            return response()->json(['guess' => 'wrong', 'guesses' => $riddle->guesses->count()]);
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
            Log::create('hint.ask',$next_hint->id,'riddles.hint',Auth::user(),$riddle);
            $user->hints()->attach($next_hint->id);
            $user->save();
        }else{
            Log::create('hint.ask.fal','','riddles.hint',Auth::user(),$riddle);
            abort(403);
        }

        return redirect(route('riddle', ['riddle' => $riddle]));
    }

    public function deleteHint(Riddle $riddle, Hint $hint)
    {
        Log::create('delete.hint',$hint->id,'riddles.hint.delete',Auth::user(),$riddle);

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

            Log::create('add.hint',$hint->id,'riddles.hint.add',Auth::user(),$riddle);
        }else{

            Log::create('add.hint.attempt','','riddles.hint.add',Auth::user(),$riddle);
            abort(403);
        }

        return redirect(route('users.riddles', ['riddle' => $riddle, 'option' => 'hint']));
    }

    public function approve(Riddle $riddle, $return = null)
    {
        Log::create('riddle.approve','','riddles.approve',Auth::user(),$riddle);

        if(Auth::user()->moderator!=1)
        {
            abort(403);
        }

        $riddle->approved = 1;
        $riddle->approved_by = Auth::user()->id;
        $riddle->approved_at = date("Y-m-d H:i:s");
        Auth::user()->points += 15;
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
        Log::create('riddle.block',$request->input('reason'),'riddles.block',Auth::user(),$riddle);

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
            Log::create('riddle.edit.attempt','','riddle.edit',Auth::user(),$riddle);

            abort(403);
        }
        Log::create('riddle.edit','','riddle.edit',Auth::user(),$riddle);

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
        $next_riddle = Auth::user()->unlockNextRiddle();

        if($next_riddle == null) {
          return redirect(route('riddles.noneleft'));
        } else {
          return redirect(route('riddle', ['riddle' => $next_riddle]));
        }
    }

    public function all()
    {
        Log::create('page.view','','riddles.all',Auth::user());

        $active_riddles = Auth::user()->activeRiddles()->get();
        $solved_riddles = Auth::user()->solvedRiddles()->get();
        $difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

        return view('riddles.all', [
          'active_riddles' => $active_riddles,
          'solved_riddles' => $solved_riddles,
          'difficulties' => $difficulties
        ]);
    }

    public function sequence()
    {
        Log::create('page.view','','riddles.sequence',Auth::user());

        $sequenced_riddles = Riddle::all()->where('number','!=',null)->sortByDesc('number');
        $unsequenced_riddles = Riddle::all()->diff($sequenced_riddles)->where('approved','1')->where('blocked','0');
        $riddles = Riddle::all();
        $last_number = Riddle::all()->max('number');

        return view('riddles.sequence', [
            'sequenced_riddles' => $sequenced_riddles,
            'unsequenced_riddles' => $unsequenced_riddles,
            'last_number' => $last_number,
            'riddles' => $riddles
        ]);
    }

    public function addToSequence(Riddle $riddle)
    {
        Log::create('sequence.add.riddle','','riddle.add.sequence',Auth::user());

        $last_number = Riddle::all()->max('number');

        $riddle->number = $last_number + 1;
        $riddle->save();

        return redirect()->back();
    }

    public function sequenceUp(Riddle $riddle)
    {
        Log::create('sequence.move.riddle.up','','riddles.move.up',Auth::user(),$riddle);

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
        Log::create('sequence.move.riddle.down','','riddles.move.down',Auth::user(),$riddle);

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

    public function duplicate(Request $request)
    {
        $riddle = $request->input('riddle_id');

        Log::create('add.duplicate','','riddles.duplicate.add',Auth::user(),$riddle);

        if(!Auth::user()->solvedRiddles->contains($riddle) || !Auth::user()->solvedRiddles->contains($request->input('similar_to'))) {
            abort(403);
        }

        $duplicate = new Duplicate();
        $duplicate->duplicate_id = $riddle;
        $duplicate->riddle_id = $request->input('similar_to');
        $duplicate->user_id = Auth::user()->id;
        $duplicate->save();

        return redirect(route('riddles.next'));
    }

    public function duplicates()
    {
        Log::create('page.view','','riddles.duplicates',Auth::user());

        $duplicates = Duplicate::all()->groupBy('duplicate_id','riddle_id')->all();

        return view('riddles.duplicates',[
            'duplicates' => $duplicates
        ]);
    }

    public function deleteReport(Duplicate $duplicate)
    {
        Log::create('delete.duplicate.report',$duplicate->id,'riddles.delete.duplicate',Auth::user());

        $duplicates = Duplicate::all()->where('riddle_id',$duplicate->riddle_id)->where('duplicate_id',$duplicate->duplicate_id)->all();
        foreach($duplicates as $duplicate){
            $duplicate->delete();
        }

        return redirect()->back();
    }

    public function deleteRiddle(Riddle $riddle)
    {
        Log::create('delete.riddle','','riddles.delete',Auth::user(),$riddle);

        if($riddle->number!=null)
        {
            $riddles_after = Riddle::all()->where('number','>',$riddle->number)->sortBy('number')->all();
            $riddle->delete();
            foreach($riddles_after as $riddle_after){
                $riddle_after->number = $riddle_after->number-1;
                $riddle_after->save();
            }
        }else{
            $riddle->delete();
        }

        return redirect()->back();
    }

    public function help()
    {

        $user = Auth::user();
        $riddle = $user->current_riddle();

        if($riddle->helps()->where('user_id',$user->id)->count()>0){
            Log::create('riddle.help.attempt','','riddles.help',Auth::user(),$riddle);

            abort(403);
        }

        if($riddle->guesses()->where('user_id',$user->id)->count()<6){
            Log::create('riddle.help.attempt','','riddles.help',Auth::user(),$riddle);
            abort(403);
        }

        $help = new Help();
        $help->user_id = $user->id;
        $help->riddle_id = $riddle->id;
        $help->save();

        Log::create('riddle.help',$help->id,'riddles.help',Auth::user(),$riddle);
        return redirect()->back();
    }

    public function sendHelp(Request $request)
    {

        $help = Help::find($request->input('help'));
        $help->help = $request->input('help_text');
        $help->helped_by = Auth::user()->id;
        $help->helped_at = date('Y-m-d H:i:s');
        $help->save();

        Log::create('send.help',$help->id,'riddles.send.help',Auth::user());
        return redirect()->back();
    }
}
