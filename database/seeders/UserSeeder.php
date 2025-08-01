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
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            // 'division_id' => 1, 
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        // Membuat Project Manager
        $pm = User::create([
            'name' => 'Project Manager',
            'email' => 'pm@example.com',
            'password' => bcrypt('password'),
            // 'division' => 'Management', 
            'is_active' => true,
        ]);
        $pm->assignRole('Project Manager');

        // Membuat Developer
        $developer = User::create([
            'name' => 'Developer',
            'email' => 'developer@example.com',
            'password' => bcrypt('password'),
            // 'division' => 'IT', 
            'is_active' => true,
        ]);
        $developer->assignRole('Developer');

        // Membuat QA
        $qa = User::create([
            'name' => 'QA',
            'email' => 'qa@example.com',
            'password' => bcrypt('password'),
            // 'division' => 'IT', 
            'is_active' => true,
        ]);
        $qa->assignRole('QA');
    }
}