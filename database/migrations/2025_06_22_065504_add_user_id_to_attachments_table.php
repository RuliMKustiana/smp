<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            // Menambahkan kolom user_id setelah kolom task_id
            // onDelete('set null') berarti jika user dihapus, nilai di sini menjadi NULL.
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('task_id')
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum menghapus kolomnya
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
