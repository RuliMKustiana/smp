<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            // Tambahkan kolom 'report_id' yang terhubung ke tabel 'reports'
            $table->foreignId('report_id')
                  ->nullable() // Buat nullable jika beberapa lampiran mungkin bukan milik laporan
                  ->constrained('reports') // Terhubung ke tabel 'reports'
                  ->onDelete('cascade'); // Hapus lampiran jika laporan dihapus
        });
    }

    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign(['report_id']);
            $table->dropColumn('report_id');
        });
    }
};