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
            // 'group_join_request',      7. Someone requests to join your group (academic)
            // 'reply_notification',      8. Replies to your posts/comments (social)
            // 'friend_notification',     9. Friend request sent/accepted/rejected (social)
            // 'follow_notification',     10. Someone follows you (social)
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
}
