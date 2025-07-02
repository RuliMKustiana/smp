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
        Schema::table('attachments', function (Blueprint $table) {
            // Hapus kolom 'task_id' yang konflik jika ada.
            // Kolom ini tidak diperlukan untuk relasi polimorfik.
            if (Schema::hasColumn('attachments', 'task_id')) {
                // Hapus foreign key constraint terlebih dahulu jika ada
                // Nama constraint bisa bervariasi, ini adalah nama default Laravel
                try {
                    $table->dropForeign(['task_id']);
                } catch (\Exception $e) {
                    // Abaikan jika constraint tidak ditemukan
                }
                $table->dropColumn('task_id');
            }

            // Pastikan kolom polimorfik ada
            if (!Schema::hasColumn('attachments', 'attachable_id')) {
                $table->unsignedBigInteger('attachable_id')->after('id');
            }
            if (!Schema::hasColumn('attachments', 'attachable_type')) {
                $table->string('attachable_type')->after('attachable_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            // Opsi untuk mengembalikan kolom jika migrasi di-rollback
            if (!Schema::hasColumn('attachments', 'task_id')) {
                $table->foreignId('task_id')->nullable()->after('id');
            }
        });
    }
};
