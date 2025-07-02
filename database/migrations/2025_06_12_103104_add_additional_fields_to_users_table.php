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
            $table->string('username')->unique()->after('name');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade')->after('password');
            $table->string('division')->nullable()->after('role_id');
            $table->string('phone_number')->nullable()->after('division');
            $table->string('profile_photo_path', 2048)->nullable()->after('phone_number');
            $table->boolean('is_active')->default(true)->after('profile_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['username', 'role_id', 'division', 'phone_number', 'profile_photo_path', 'is_active']);
        });
    }
};
