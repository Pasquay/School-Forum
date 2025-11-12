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
        Schema::table('submission_comments', function (Blueprint $table) {
            // Rename 'comment' to 'comment_text'
            $table->renameColumn('comment', 'comment_text');
            // Add 'is_private' column
            $table->boolean('is_private')->default(false)->after('comment_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission_comments', function (Blueprint $table) {
            $table->renameColumn('comment_text', 'comment');
            $table->dropColumn('is_private');
        });
    }
};
