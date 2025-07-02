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
        Schema::table('task_updates', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan link setelah kolom 'description'
            $table->string('link')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_updates', function (Blueprint $table) {
            //
        });
    }
};
