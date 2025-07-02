<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            // PERBAIKAN: Cek dulu sebelum menghapus
            if (Schema::hasColumn('attachments', 'attachable_id')) {
                $table->dropColumn('attachable_id');
            }
            if (Schema::hasColumn('attachments', 'attachable_type')) {
                $table->dropColumn('attachable_type');
            }
            
            // PERBAIKAN: Hapus juga kolom 'report_id' yang lama jika ada
            if (Schema::hasColumn('attachments', 'report_id')) {
                // Hapus foreign key constraint dulu sebelum drop kolomnya
                $table->dropForeign(['report_id']);
                $table->dropColumn('report_id');
            }

            // Tambahkan kolom baru hanya jika belum ada
            if (!Schema::hasColumn('attachments', 'task_id')) {
                $table->foreignId('task_id')->after('id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn('task_id');
            $table->morphs('attachable');
        });
    }
};
