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
        Schema::table('groups', function (Blueprint $table) {
            $table->boolean('members_can_post')->default(true);
            $table->boolean('members_can_comment')->default(true);
            $table->boolean('members_can_invite')->default(false);
            $table->boolean('auto_approve_posts')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['members_can_post', 'members_can_comment', 'members_can_invite', 'auto_approve_posts']);
        });
    }
};
