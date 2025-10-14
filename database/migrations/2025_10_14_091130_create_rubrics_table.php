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
        Schema::create('rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->string('criteria_name');
            $table->text('description')->nullable();
            $table->decimal('max_points', 8, 2);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('rubric_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubric_id')->constrained('rubrics')->onDelete('cascade');
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade');
            $table->decimal('points_earned', 8, 2);
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubric_scores');
        Schema::dropIfExists('rubrics');
    }
};
