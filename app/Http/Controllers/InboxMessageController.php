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
    public function showInbox()
    {
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

    public function respond($id, Request $request)
    {
        $message = InboxMessage::findOrFail($id);
        $action = strtolower($request->input('action', ''));

        if ($message->recipient_id === Auth::id()) {
            $responseSuccess = true;
            $responseMessage = 'Notification acknowledged.';

            switch ($message->type) {
                case 'group_join_request':
                    $user = User::findOrFail($message->sender_id);
                    $group = Group::findOrFail($message->group_id);
                    if ($action === 'accept') {
                        $membership = $user->groups()
                            ->where('groups.id', $group->id)
                            ->exists();

                        if (!$membership) {
                            $user->groups()
                                ->attach($group->id, [
                                    'role' => 'member',
                                    'is_starred' => false,
                                    'is_muted' => false,
                                ]);
                            $group->update(['member_count' => $group->getMemberCount()]);

                            $responseMessage = "Group join request accepted.";
                            $responseSuccess = true;
                            $responseBody = "Your join request to <a href='/group/$group->id'>$group->name</a> has been accepted.";
                        } else {
                            $responseMessage = "Failed to add user to group.";
                            $responseSuccess = false;
                        }
                    } else {
                        $responseMessage = "Group join request rejected.";
                        $responseSuccess = true;
                        $responseBody = "Your join request to <a href='/group/$group->id'>$group->name</a> has been rejected.";
                    }

                    InboxMessage::where('sender_id', $message->sender_id)
                        ->where('group_id', $message->group_id)
                        ->where('type', 'group_join_request')
                        ->where('responded', false)
                        ->update(['responded' => true]);

                    InboxMessage::create([
                        'sender_id' => $message->recipient_id,
                        'recipient_id' => $message->sender_id,
                        'group_id' => $message->group_id,
                        'type' => 'moderator_action',
                        'title' => "<a href='/group/$group->id'>$group->name</a> join request response",
                        'body' => $responseBody,
                    ]);

                    break;
                case 'group_invitation':
                    $user = User::findOrFail($message->recipient_id);
                    $group = Group::findOrFail($message->group_id);
                    if ($action === 'accept') {
                        $membership = $user->groups()
                            ->where('groups.id', $group->id)
                            ->exists();

                        if (!$membership) {
                            $user->groups()
                                ->attach($group->id, [
                                    'role' => 'member',
                                    'is_starred' => false,
                                    'is_muted' => false,
                                ]);
                            $group->update(['member_count' => $group->getMemberCount()]);

                            $responseMessage = "Group invite accepted.";
                            $responseSuccess = true;
                            $responseBody = "Your invite to <a href='/group/$group->id'>$group->name</a> has been accepted.";
                        } else {
                            $responseMessage = "Failed to add your account to group.";
                            $responseSuccess = false;
                        }
                    } else {
                        $responseMessage = "Group invite rejected.";
                        $responseSuccess = true;
                        $responseBody = "Your invite to <a href='/group/$group->id'>$group->name</a> has been rejected.";
                    }

                    InboxMessage::where('sender_id', $message->sender_id)
                        ->where('group_id', $message->group_id)
                        ->where('type', 'group_invitation')
                        ->where('responded', false)
                        ->update(['responded' => true]);

                    InboxMessage::create([
                        'sender_id' => $message->recipient_id,
                        'recipient_id' => $message->sender_id,
                        'group_id' => $message->group_id,
                        'type' => 'moderator_action',
                        'title' => "<a href='/group/$group->id'>$group->name</a> invite response",
                        'body' => $responseBody,
                    ]);

                    break;
                case 'moderator_action':
                    $group = Group::findOrFail($message->group_id);

                    $responseMessage = "Group \"{$group->name}\" moderator promotion accepted.";
                    $responseSuccess = true;
                    break;
                case 'assignment_post':
                    $responseMessage = 'Assignment notification marked as read.';
                    break;
                case 'group_post_notification':
                    $responseMessage = 'Post notification marked as read.';
                    break;
                default:
                    $responseMessage = 'Notification marked as read.';
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
