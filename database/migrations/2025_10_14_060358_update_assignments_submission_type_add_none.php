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

        if ($driver === 'sqlite') {
            // SQLite: Just ensure the column exists
            Schema::table('assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('assignments', 'submission_type')) {
                    $table->string('submission_type')->default('text')->after('visibility');
                }
            });
        } else {
            // MySQL: Use raw SQL to modify ENUM
            DB::statement("ALTER TABLE `assignments` MODIFY COLUMN `submission_type` ENUM('text', 'file', 'external_link', 'none') DEFAULT 'text'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite') {
            // MySQL: Revert back to original ENUM
            DB::statement("ALTER TABLE `assignments` MODIFY COLUMN `submission_type` ENUM('text', 'file', 'external_link') DEFAULT 'text'");
        }
    }
};
