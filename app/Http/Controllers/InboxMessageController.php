<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\InboxMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GroupController;

class InboxMessageController extends Controller
{
    public function showInbox(){
        // unread messages
        $unreadMessages = InboxMessage::forUser(Auth::id())
                                      ->where('responded', false);
        // read messages
        $readMessages = InboxMessage::forUser(Auth::id())
                                      ->where('responded', true);
        return view('inbox', compact(
            'unreadMessages',
            'readMessages',
        ));
    }

    public function respond($id, $type, Request $request){
        $message = InboxMessage::findOrFail($id);
        $action = $request->input('action');

        if($message->recipient_id === Auth::id()){
            $responseMessage = "";

            switch($message->type){
                case 'group_join_request':
                    if($action === 'accept'){
                        $user = User::findOrFail($message->sender_id);
                        $group = Group::findOrFail($message->group_id);
                        $membership = $user->groups()
                                           ->where('groups.id', $group->id)
                                           ->exists();
                        
                        if(!$membership){
                            $user->groups()
                                ->attach($group->id, [
                                    'role' => 'member',
                                    'is_starred' => false,
                                    'is_muted' => false,
                                ]);
                            $group->update(['member_count' => $group->getMemberCount()]);
                            
                            $responseMessage = "Group join request accepted." ;
                            $responseSuccess = true;
                        } else {
                            $responseMessage = "Failed to add user to group." ;
                            $responseSuccess = false;
                        }
                    } else {
                        $responseMessage = "Group join request rejected.";
                        $responseSuccess = true;
                    }
                    break;
                case '':
                    break;
            }

            $message->responded = true;
            $message->save();

            return response()->json([
                'success' => $responseSuccess,
                'message' => $responseMessage,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }
    }
}
 