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
        // Convert all existing UTC dates to Pacific Time (UTC-7)
        // Subtract 7 hours (25200 seconds) from all dates
        $assignments = DB::table('assignments')->get();

        foreach ($assignments as $assignment) {
            $updates = [];

            if ($assignment->date_assigned) {
                $updates['date_assigned'] = date('Y-m-d H:i:s', strtotime($assignment->date_assigned) - 25200);
            }

            if ($assignment->date_due) {
                $updates['date_due'] = date('Y-m-d H:i:s', strtotime($assignment->date_due) - 25200);
            }

            if ($assignment->close_date) {
                $updates['close_date'] = date('Y-m-d H:i:s', strtotime($assignment->close_date) - 25200);
            }

            if (!empty($updates)) {
                DB::table('assignments')->where('id', $assignment->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to UTC (add 7 hours)
        $assignments = DB::table('assignments')->get();

        foreach ($assignments as $assignment) {
            $updates = [];

            if ($assignment->date_assigned) {
                $updates['date_assigned'] = date('Y-m-d H:i:s', strtotime($assignment->date_assigned) + 25200);
            }

            if ($assignment->date_due) {
                $updates['date_due'] = date('Y-m-d H:i:s', strtotime($assignment->date_due) + 25200);
            }

            if ($assignment->close_date) {
                $updates['close_date'] = date('Y-m-d H:i:s', strtotime($assignment->close_date) + 25200);
            }

            if (!empty($updates)) {
                DB::table('assignments')->where('id', $assignment->id)->update($updates);
            }
        }
    }
};
