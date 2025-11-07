<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'sqlite') {
            // Fix assignment_type enum to include all types used in the form
            DB::statement("ALTER TABLE assignments MODIFY COLUMN assignment_type ENUM('essay', 'quiz', 'assignment', 'discussion', 'exam', 'project', 'homework', 'presentation') NOT NULL");

            // Fix submission_type enum to include all types
            DB::statement("ALTER TABLE assignments MODIFY COLUMN submission_type ENUM('text', 'file', 'external_link', 'none', 'quiz') DEFAULT 'text'");
        } else {
            // On sqlite during tests, fallback to VARCHAR columns is acceptable; nothing to do.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'sqlite') {
            // Revert to original enums
            DB::statement("ALTER TABLE assignments MODIFY COLUMN assignment_type ENUM('essay', 'quiz', 'assignment', 'discussion', 'exam', 'project') NOT NULL");
            DB::statement("ALTER TABLE assignments MODIFY COLUMN submission_type ENUM('text', 'file', 'external_link') DEFAULT 'text'");
        }
    }
};
