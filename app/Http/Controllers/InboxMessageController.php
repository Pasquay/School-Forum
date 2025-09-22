<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InboxMessageController extends Controller
{
    public function showInbox(){
        return view('inbox');
    }
}
