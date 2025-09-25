<?php

namespace App\Http\Controllers;

use App\Models\InboxMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InboxMessageController extends Controller
{
    public function showInbox(){
        $messages = InboxMessage::forUser(Auth::id());
        return view('inbox', compact(
            'messages',
        ));
    }
}
 