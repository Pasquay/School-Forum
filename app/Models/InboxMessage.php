<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'group_id',
        'type',
        // 'announcement',            1. Site announcements/events (school-wide)
        // 'moderator_action',        2. Moderator actions/warnings (official)
        // 'grade_update',            3. Grade update notification (academic)
        // 'assignment_post',         4. Assignment post notification (academic)
        // 'group_post_notification', 5. Teacher/staff post in group/class you joined (academic)
        // 'group_request_status',    6. Your request to join a group accepted/denied (academic)
        // 'group_join_request',      7. Someone requests to join your group
        // 'reply_notification',      8. Replies to your posts/comments (social)
        // 'friend_notification',     9. Friend request sent/accepted/rejected (social)
        // 'follow_notification',     10. Someone follows you (social)
        // 'group_invite',            11. You are invited to a group
        'title',
        'body',
        'read_at',
    ];

    /**
     * RELATIONS
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * HELPER FUNCTIONS
     */
    public static function forUser($userId)
    {
        return self::where('recipient_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function hasPendingGroupJoinRequest($userId, $groupId)
    {
        return self::where('sender_id', $userId)
            ->where('type', 'group_join_request')
            ->where('group_id', $groupId)
            ->where('responded', false)
            ->exists();
    }

    /**
     * Send a notification message to multiple members of a group.
     *
     * @param  \App\Models\Group  $group
     * @param  \App\Models\User   $sender
     * @param  string               $type
     * @param  string               $title
     * @param  string               $body
     * @param  array                $options  Supported keys: roles (array), exclude_sender (bool)
     * @return void
     */
    public static function notifyGroupMembers(Group $group, User $sender, string $type, string $title, string $body, array $options = []): void
    {
        $roles = $options['roles'] ?? ['member'];
        $excludeSender = $options['exclude_sender'] ?? true;

        $membersQuery = $group->members();

        if (!empty($roles)) {
            $membersQuery->wherePivotIn('role', $roles);
        }

        if ($excludeSender) {
            $membersQuery->where('users.id', '!=', $sender->id);
        }

        $recipientIds = $membersQuery->pluck('users.id')->unique()->values();

        if ($recipientIds->isEmpty()) {
            return;
        }

        $timestamp = now();

        $messages = $recipientIds->map(function ($recipientId) use ($group, $sender, $type, $title, $body, $timestamp) {
            return [
                'sender_id' => $sender->id,
                'recipient_id' => $recipientId,
                'group_id' => $group->id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        })->all();

        self::insert($messages);
    }
}
