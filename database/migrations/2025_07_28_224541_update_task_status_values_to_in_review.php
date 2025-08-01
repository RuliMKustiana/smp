<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah 'Belum Dikerjakan' menjadi 'To-Do'
        DB::table('tasks')
            ->where('status', 'Belum Dikerjakan')
            ->update(['status' => 'To-Do']);

        // Mengubah 'Selesai' menjadi 'Completed'
        DB::table('tasks')
            ->where('status', 'Selesai')
            ->update(['status' => 'Completed']);
            
        // Mengubah 'Revisi' menjadi 'In Review'
        DB::table('tasks')
            ->where('status', 'Revisi')
            ->update(['status' => 'In Review']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tasks')
            ->where('status', 'To-Do')
            ->update(['status' => 'Belum Dikerjakan']);

        DB::table('tasks')
            ->where('status', 'Completed')
            ->update(['status' => 'Selesai']);
            
        DB::table('tasks')
            ->where('status', 'In Review')
            ->update(['status' => 'Revisi']);
    }
};
