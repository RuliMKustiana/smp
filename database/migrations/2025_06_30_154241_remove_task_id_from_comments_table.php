<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Periksa apakah tabel 'comments' memiliki kolom 'task_id' sebelum mencoba menghapusnya
        if (Schema::hasColumn('comments', 'task_id')) {
            Schema::table('comments', function (Blueprint $table) {
                // PERBAIKAN: Hapus foreign key constraint terlebih dahulu
                // Laravel akan mencoba mencari constraint bernama 'comments_task_id_foreign'
                $table->dropForeign(['task_id']);

                // Setelah constraint dihapus, baru hapus kolomnya.
                $table->dropColumn('task_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Jika migrasi di-rollback, tambahkan kembali kolomnya (opsional, tapi praktik yang baik)
        if (!Schema::hasColumn('comments', 'task_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->unsignedBigInteger('task_id')->nullable()->after('updated_at');
                // Anda bisa menambahkan kembali foreign key di sini jika diperlukan
                // $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            });
        }
    }
};
