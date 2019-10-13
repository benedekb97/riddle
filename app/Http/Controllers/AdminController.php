<?php

namespace App\Http\Controllers;

use App\Models\StaticMessage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function staticMessages()
    {
        $messages = StaticMessage::all()->where('active','1');
        $message_types = ['info','danger','warning','success','primary','default'];
        $message_icons = ['fa-info-circle','fa-exclamation-triangle','fa-exclamation-circle','fa-check','fa-info-circle','fa-info-circle'];

        return view('admin.static_messages',[
            'messages' => $messages,
            'message_types' => $message_types,
            'message_icons' => $message_icons
        ]);
    }

    public function deleteStaticMessage(Request $request)
    {
        $id = $request->input('id');
        StaticMessage::all()->find($id)->first()->delete();

        return redirect()->back();
    }

    public function editStaticMessage(Request $request)
    {
        $id = $request->input('id');
        $message = StaticMessage::all()->find($id)->first();
        $message->title = $request->input('title');
        $message->message = $request->input('message');
        $message->type = $request->input('type');
        $message->save();

        return redirect()->back();
    }

    public function newStaticMessage(Request $request)
    {
        $message = new StaticMessage();
        $message->title = $request->input('title');
        $message->message = $request->input('message');
        $message->type = $request->input('type');
        $message->active = 1;
        $message->save();

        return redirect()->back();
    }
}
