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
        $adminRole = Role::create(['name' => 'Admin']);
        $pmRole = Role::create(['name' => 'Project Manager']);
        $developerRole = Role::create(['name' => 'Developer']);
        $qaRole = Role::create(['name' => 'QA']);
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