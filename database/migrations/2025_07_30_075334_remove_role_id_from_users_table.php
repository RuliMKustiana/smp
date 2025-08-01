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
        Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'role_id')) {
            $table->dropColumn('role_id');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'role_id')) {
            $table->foreignId('role_id')->nullable()->after('password'); // Sesuaikan posisi jika perlu
        }
    });
}
};
