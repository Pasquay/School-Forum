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
            $table->boolean('is_group_assignment')->default(false)->after('assignment_type');
            $table->integer('group_size_min')->nullable()->after('is_group_assignment');
            $table->integer('group_size_max')->nullable()->after('group_size_min');
        });

        Schema::create('group_assignment_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['submission_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_assignment_members');

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['is_group_assignment', 'group_size_min', 'group_size_max']);
        });
    }
};
