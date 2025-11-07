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
        Schema::table('assignments', function (Blueprint $table) {
            // Add 'quiz' to submission_type enum
            $table->enum('submission_type', ['text', 'file', 'external_link', 'none', 'quiz'])->default('text')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Revert back to without 'quiz'
            $table->enum('submission_type', ['text', 'file', 'external_link', 'none'])->default('text')->change();
        });
    }
};
