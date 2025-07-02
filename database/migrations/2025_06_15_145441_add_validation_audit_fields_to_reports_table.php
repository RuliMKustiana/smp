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
        Schema::table('reports', function (Blueprint $table) {
            // Kolom untuk menyimpan ID user yang melakukan validasi
            // Diletakkan setelah kolom 'validation_notes'
            $table->foreignId('validator_id')
                  ->nullable()
                  ->after('validation_notes')
                  ->constrained('users') // Membuat foreign key ke tabel users
                  ->onDelete('set null'); // Jika user dihapus, ID di sini jadi NULL

            // Kolom untuk menyimpan waktu validasi
            $table->timestamp('validated_at')->nullable()->after('validator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu sebelum menghapus kolomnya
            $table->dropForeign(['validator_id']);
            $table->dropColumn(['validator_id', 'validated_at']);
        });
    }
};