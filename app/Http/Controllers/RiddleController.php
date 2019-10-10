<?php

namespace App\Http\Controllers;

use App\Models\Riddle;
use Faker\Provider\Image;
use File;
use Illuminate\Http\Request;
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
            'message' => 'Konkrétan mindent elbasztál tesó... maximum 5MB-os lehet a fájl, aminek jp(e)g, gif, vagy png kiterjesztésűnek kell lennie, és adjál már egy címet a gyökér riddle-ödnek.'
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

        if($error != null){
            return view('riddles.new', [
                'error_message' => $this->errors[$error]['message']
            ]);
        }else{
            return view('riddles.new');
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

            $riddle = new Riddle();
            $riddle->title = $title;
            $riddle->image = $file_name;
            $riddle->user_id = Auth::id();
            $riddle->answer = $answer;
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
}
