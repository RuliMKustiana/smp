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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('deadline_date');
            $table->foreignId('project_manager_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Belum Dimulai', 'In Progress', 'Selesai', 'Revisi', 'Dibatalkan'])->default('Belum Dimulai');
            $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
