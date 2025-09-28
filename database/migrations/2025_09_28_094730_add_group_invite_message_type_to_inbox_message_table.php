<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inbox_messages', function (Blueprint $table) {
            // Modify the enum to include group_invitation
            $table->enum('type', [
                'announcement',            // 1. Site announcements/events (school-wide)
                'moderator_action',        // 2. Moderator actions/warnings (official)
                'grade_update',            // 3. Grade update notification (academic)
                'assignment_post',         // 4. Assignment post notification (academic)
                'group_post_notification', // 5. Teacher/staff post in group/class you joined (academic)
                'group_request_status',    // 6. Your request to join a group accepted/denied (academic)
                'group_join_request',      // 7. Someone requests to join your group (academic)
                'group_invitation',        // 8. Someone invites you to join their group (academic)
                'reply_notification',      // 9. Replies to your posts/comments (social)
                'friend_notification',     // 10. Friend request sent/accepted/rejected (social)
                'follow_notification',     // 11. Someone follows you (social)
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbox_messages', function (Blueprint $table) {
            // Revert back to original enum without group_invitation
            $table->enum('type', [
                'announcement',
                'moderator_action',
                'grade_update',
                'assignment_post',
                'group_post_notification',
                'group_request_status',
                'group_join_request',
                'reply_notification',
                'friend_notification',
                'follow_notification',
            ])->change();
        });
    }
};
