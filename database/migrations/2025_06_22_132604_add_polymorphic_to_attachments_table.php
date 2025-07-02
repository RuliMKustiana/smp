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
            // Hapus foreign key lama jika ada (misal: task_id)
            // Cek dulu nama constraint-nya di database Anda.
            // $table->dropForeign(['task_id']);
            // $table->dropColumn('task_id');

            // Tambahkan kolom polimorfik. Ini akan menggantikan 'report_id', 'task_id', dll.
            // 'attachable_id' akan menyimpan ID (misalnya ID dari report atau task).
            // 'attachable_type' akan menyimpan nama model (misalnya 'App\Models\Report').
            $table->morphs('attachable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropMorphs('attachable');
            // Jika perlu, kembalikan kolom lama saat rollback
            // $table->foreignId('task_id')->constrained()->onDelete('cascade');
        });
    }
};
