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
            // Modify assignment_type to be an enum with the new values
            $table->enum('assignment_type', ['essay', 'quiz', 'assignment', 'discussion', 'exam', 'project'])->change();
            
            // Add new fields
            $table->timestamp('close_date')->nullable()->after('date_due');
            $table->longText('description')->nullable()->change(); // Change to longText and make nullable
            $table->enum('visibility', ['draft', 'published'])->default('draft')->after('close_date');
            $table->enum('submission_type', ['text', 'file', 'external_link'])->default('text')->after('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Revert assignment_type back to string
            $table->string('assignment_type', 50)->change();
            
            // Remove the new fields
            $table->dropColumn(['close_date', 'visibility', 'submission_type']);
            
            // Revert description back to text and not nullable
            $table->text('description')->nullable(false)->change();
        });
    }
};
