<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function receive(Request $request)
    {
        var_dump($request);
    }
}
