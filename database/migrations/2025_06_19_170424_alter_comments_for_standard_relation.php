<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Hapus kolom polimorfik
            $table->dropMorphs('commentable');
            // Tambahkan foreign key biasa ke tasks
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn('task_id');
            $table->morphs('commentable');
        });
    }
};
