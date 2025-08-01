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
        Schema::table('comments', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu jika ada
            // Nama constraint bisa bervariasi, cek nama di database Anda.
            // Contoh nama: comments_task_id_foreign
            $table->dropForeign(['task_id']);

            // Hapus kolomnya
            $table->dropColumn('task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Jika ingin bisa di-rollback, tambahkan kolomnya kembali
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};