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
        Schema::table('tasks', function (Blueprint $table) {
            // Tambahkan kolom ini setelah 'deadline'
            // Menggunakan decimal untuk menyimpan angka seperti 8.5 jam
            $table->decimal('estimated_hours', 8, 2)->nullable()->after('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Tambahkan ini untuk bisa di-rollback
            $table->dropColumn('estimated_hours');
        });
    }
};