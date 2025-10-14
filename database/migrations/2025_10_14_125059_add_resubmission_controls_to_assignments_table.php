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
            $table->boolean('allow_resubmissions')->default(true)->after('allow_late_submissions');
            $table->integer('max_attempts')->default(-1)->after('allow_resubmissions')->comment('-1 means unlimited attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['allow_resubmissions', 'max_attempts']);
        });
    }
};
