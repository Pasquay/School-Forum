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
        Schema::create('inbox_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->enum('type', [
                'announcement',            // 1. Site announcements/events (school-wide)
                'moderator_action',        // 2. Moderator actions/warnings (official)
                'grade_update',            // 3. Grade update notification (academic)
                'assignment_post',         // 4. Assignment post notification (academic)
                'group_post_notification', // 5. Teacher/staff post in group/class you joined (academic)
                'group_request_status',    // 6. Your request to join a group accepted/denied (academic)
                'group_join_request',      // 7. Someone requests to join your group (academic)
                'reply_notification',      // 8. Replies to your posts/comments (social)
                'friend_notification',     // 9. Friend request sent/accepted/rejected (social)
                'follow_notification',     // 10. Someone follows you (social)
            ]);
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_messages');
    }
};
