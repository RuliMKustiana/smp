<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $pmRole = Role::where('slug', 'pm')->first();
        $employeeRole = Role::where('slug', 'employee')->first();

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@sistem.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@sistem.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'division' => 'IT',
                'phone_number' => '081234567890',
                'is_active' => true,
            ]
        );

        // Create Project Manager
        User::updateOrCreate(
            ['email' => 'pm@sistem.com'],
            [
                'name' => 'Project Manager',
                'username' => 'pm',
                'email' => 'pm@sistem.com',
                'password' => Hash::make('password'),
                'role_id' => $pmRole->id,
                'division' => 'Development',
                'phone_number' => '081234567891',
                'is_active' => true,
            ]
        );

        // Create Employee
        User::updateOrCreate(
            ['email' => 'employee@sistem.com'],
            [
                'name' => 'Karyawan',
                'username' => 'employee',
                'email' => 'employee@sistem.com',
                'password' => Hash::make('password'),
                'role_id' => $employeeRole->id,
                'division' => 'Development',
                'phone_number' => '081234567892',
                'is_active' => true,
            ]
        );

        // Create additional employees for testing
        User::updateOrCreate(
            ['email' => 'employee2@sistem.com'],
            [
                'name' => 'Karyawan 2',
                'username' => 'employee2',
                'email' => 'employee2@sistem.com',
                'password' => Hash::make('password'),
                'role_id' => $employeeRole->id,
                'division' => 'Frontend',
                'phone_number' => '081234567893',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'employee3@sistem.com'],
            [
                'name' => 'Karyawan 3',
                'username' => 'employee3',
                'email' => 'employee3@sistem.com',
                'password' => Hash::make('password'),
                'role_id' => $employeeRole->id,
                'division' => 'Backend',
                'phone_number' => '081234567894',
                'is_active' => true,
            ]
        );
    }
}
