<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('task_updates', function (Blueprint $table) {
        // Cek dulu apakah kolom 'link' BELUM ada
        if (!Schema::hasColumn('task_updates', 'link')) {
            // Jika belum ada, baru tambahkan
            $table->string('link')->nullable()->after('description');
        }
    });
}

    public function down(): void
    {
        // Kosongkan saja method down ini, karena ini hanya perbaikan satu arah
    }
};
