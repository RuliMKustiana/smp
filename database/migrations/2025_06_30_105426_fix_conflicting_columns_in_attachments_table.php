<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            // Memeriksa dan menghapus kolom 'task_id' yang lama jika ada.
            if (Schema::hasColumn('attachments', 'task_id')) {
                
                // Mencoba menghapus foreign key terlebih dahulu.
                // Dibungkus dalam try-catch untuk menangani kasus jika key sudah tidak ada.
                try {
                    // Nama constraint default Laravel adalah 'nama_tabel_nama_kolom_foreign'
                    $table->dropForeign('attachments_task_id_foreign');
                } catch (\Exception $e) {
                    // Jika gagal (karena tidak ada), tidak masalah. Lanjutkan saja.
                    // Anda bisa menambahkan log atau pesan jika perlu.
                    // echo "Notice: Foreign key 'attachments_task_id_foreign' tidak ditemukan. Melanjutkan...\n";
                }
                
                // Setelah key (kemungkinan) sudah dihapus, hapus kolomnya.
                $table->dropColumn('task_id');
            }

            // Memastikan kolom polimorfik 'attachable' ada.
            if (!Schema::hasColumn('attachments', 'attachable_id')) {
                $table->unsignedBigInteger('attachable_id')->after('id');
            }
            if (!Schema::hasColumn('attachments', 'attachable_type')) {
                $table->string('attachable_type')->after('attachable_id');
            }
        });
    }

    /**
     * Membatalkan migrasi.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            // Menghapus kolom polimorfik
            if (Schema::hasColumn('attachments', 'attachable_id')) {
                 $table->dropColumn('attachable_id');
            }
            if (Schema::hasColumn('attachments', 'attachable_type')) {
                 $table->dropColumn('attachable_type');
            }

            // Mengembalikan kolom 'task_id' jika migrasi di-rollback
            if (!Schema::hasColumn('attachments', 'task_id')) {
                $table->foreignId('task_id')->nullable()->constrained()->after('id');
            }
        });
    }
};