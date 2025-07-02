<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._add_type_column_to_reports_table.php
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Menambahkan kolom string untuk 'type' setelah kolom 'title'
            // Bisa berisi: 'progress', 'weekly', 'monthly', 'final'
            $table->string('type')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
