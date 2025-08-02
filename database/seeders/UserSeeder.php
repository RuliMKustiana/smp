<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('Admin');

        // Membuat Project Manager
        $pm = User::firstOrCreate(
            ['email' => 'pm@example.com'],
            [
                'name' => 'Project Manager',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );
        $pm->assignRole('Project Manager');

        // Membuat Developer
        $developer = User::firstOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => 'Developer',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );
        $developer->assignRole('Developer');

        // Membuat QA
        $qa = User::firstOrCreate(
            ['email' => 'qa@example.com'],
            [
                'name' => 'QA',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );
        $qa->assignRole('QA');
    }
}