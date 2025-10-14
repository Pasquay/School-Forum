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
        Schema::table('assignments', function (Blueprint $table) use ($driver) {
            // Some previous columns may not exist in sqlite test schema; skip positioning with after() where not compatible
            if (!Schema::hasColumn('assignments', 'allow_late_submissions')) {
                $col = $table->boolean('allow_late_submissions')->default(true);
                // position hint only for non-sqlite
                if ($driver !== 'sqlite') {
                    $col->after('time_limit');
                }
            }
            if (!Schema::hasColumn('assignments', 'late_penalty_percentage')) {
                $col = $table->decimal('late_penalty_percentage', 5, 2)->nullable()->comment('Percentage to deduct for late submissions');
                if ($driver !== 'sqlite') {
                    $col->after('allow_late_submissions');
                }
            }
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('assignment_submissions', 'late_penalty_applied')) {
                $table->decimal('late_penalty_applied', 5, 2)->nullable()->after('grade')->comment('Points deducted for late submission');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'allow_late_submissions') || Schema::hasColumn('assignments', 'late_penalty_percentage')) {
                $drops = [];
                if (Schema::hasColumn('assignments', 'allow_late_submissions')) $drops[] = 'allow_late_submissions';
                if (Schema::hasColumn('assignments', 'late_penalty_percentage')) $drops[] = 'late_penalty_percentage';
                if (!empty($drops)) {
                    $table->dropColumn($drops);
                }
            }
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropColumn('late_penalty_applied');
        });
    }
};
