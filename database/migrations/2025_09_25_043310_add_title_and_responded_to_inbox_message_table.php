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
        Schema::table('inbox_messages', function (Blueprint $table) {
            $table->string('title')->nullable()->after('type');
            $table->boolean('responded')->default(false)->after('read_at');
            $table->unsignedBigInteger('group_id')->nullable()->after('recipient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbox_messages', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('responded');
            $table->dropColumn('group_id');
        });
    }
};
