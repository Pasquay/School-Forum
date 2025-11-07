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
            $table->decimal('weight', 5, 2)->default(1.00)->after('max_points')->comment('Weight for overall grade calculation');
            $table->boolean('allow_resubmission')->default(false)->after('allow_late_submissions');
            $table->integer('max_resubmissions')->nullable()->after('allow_resubmission')->comment('Maximum number of resubmissions allowed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['weight', 'allow_resubmission', 'max_resubmissions']);
        });
    }
};
