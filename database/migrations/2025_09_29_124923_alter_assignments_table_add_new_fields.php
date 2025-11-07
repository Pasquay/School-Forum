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
        $driver = Schema::getConnection()->getDriverName();

        // For SQLite, avoid change() and enum modifications which are not supported; add only missing columns.
        if ($driver === 'sqlite') {
            Schema::table('assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('assignments', 'close_date')) {
                    $table->timestamp('close_date')->nullable()->after('date_due');
                }
                if (!Schema::hasColumn('assignments', 'visibility')) {
                    $table->string('visibility')->default('draft')->after('close_date');
                }
                if (!Schema::hasColumn('assignments', 'submission_type')) {
                    $table->string('submission_type')->default('text')->after('visibility');
                }
                // Leave description as-is for sqlite; nullable text is sufficient for tests.
            });
        } else {
            Schema::table('assignments', function (Blueprint $table) {
                // Modify assignment_type to be an enum with the new values
                $table->enum('assignment_type', ['essay', 'quiz', 'assignment', 'discussion', 'exam', 'project'])->change();

                // Add new fields
                $table->timestamp('close_date')->nullable()->after('date_due');
                $table->longText('description')->nullable()->change(); // Change to longText and make nullable
                $table->enum('visibility', ['draft', 'published'])->default('draft')->after('close_date');
                $table->enum('submission_type', ['text', 'file', 'external_link'])->default('text')->after('visibility');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::table('assignments', function (Blueprint $table) {
                if (Schema::hasColumn('assignments', 'close_date')) {
                    $table->dropColumn(['close_date']);
                }
                if (Schema::hasColumn('assignments', 'visibility')) {
                    $table->dropColumn(['visibility']);
                }
                if (Schema::hasColumn('assignments', 'submission_type')) {
                    $table->dropColumn(['submission_type']);
                }
                // Skip changing column types on sqlite in down as well.
            });
        } else {
            Schema::table('assignments', function (Blueprint $table) {
                // Revert assignment_type back to string
                $table->string('assignment_type', 50)->change();

                // Remove the new fields
                $table->dropColumn(['close_date', 'visibility', 'submission_type']);

                // Revert description back to text and not nullable
                $table->text('description')->nullable(false)->change();
            });
        }
    }
};
