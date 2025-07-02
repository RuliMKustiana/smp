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
            // Menambahkan kolom polimorfik yang diperlukan.
            // 'morphs' adalah shortcut untuk membuat 'commentable_id' dan 'commentable_type'.
            // Menambahkan ->after('id') agar kolomnya rapi setelah kolom id.
            $table->morphs('commentable', 'commentable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Menghapus kolom polimorfik jika migrasi di-rollback.
            $table->dropMorphs('commentable', 'commentable_index');
        });
    }
};
