<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Permissions ---
        // Dashboard
        Permission::firstOrCreate(['name' => 'view admin dashboard']);
        Permission::firstOrCreate(['name' => 'view pm dashboard']);
        Permission::firstOrCreate(['name' => 'view developer dashboard']);
        Permission::firstOrCreate(['name' => 'view qa dashboard']);

        // Sidebar & Basic Views
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'view roles']);
        Permission::firstOrCreate(['name' => 'view divisions']);
        Permission::firstOrCreate(['name' => 'validate reports']);
        Permission::firstOrCreate(['name' => 'view projects']);
        Permission::firstOrCreate(['name' => 'view pm tasks']);
        Permission::firstOrCreate(['name' => 'view pm reports']);
        Permission::firstOrCreate(['name' => 'view own tasks']);
        Permission::firstOrCreate(['name' => 'view assigned projects']);
        
        // Actions
        Permission::firstOrCreate(['name' => 'manage users']); 
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'edit users']);
        Permission::firstOrCreate(['name' => 'delete users']);
        Permission::firstOrCreate(['name' => 'create roles']);
        Permission::firstOrCreate(['name' => 'edit roles']);
        Permission::firstOrCreate(['name' => 'delete roles']);
        Permission::firstOrCreate(['name' => 'create projects']);
        Permission::firstOrCreate(['name' => 'edit projects']);
        Permission::firstOrCreate(['name' => 'delete projects']);
        Permission::firstOrCreate(['name' => 'assign members']);
        Permission::firstOrCreate(['name' => 'view tasks']);
        Permission::firstOrCreate(['name' => 'create tasks']);
        Permission::firstOrCreate(['name' => 'delete tasks']);
        Permission::firstOrCreate(['name' => 'update tasks as developer']);
        Permission::firstOrCreate(['name' => 'update tasks as qa']);
        Permission::firstOrCreate(['name' => 'update tasks as project manager']);

        // --- Roles ---
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $pmRole = Role::firstOrCreate(['name' => 'Project Manager']);
        $devRole = Role::firstOrCreate(['name' => 'Developer']);
        $qaRole = Role::firstOrCreate(['name' => 'QA']);
        $designerRole = Role::firstOrCreate(['name' => 'UI/UX Designer']);
        $analystRole = Role::firstOrCreate(['name' => 'Data Analyst']);

        // --- Assign Permissions to Roles ---
        $adminRole->syncPermissions(Permission::all());

        $pmRole->syncPermissions([
            'view pm dashboard', 'view projects', 'create projects', 'edit projects', 'delete projects',
            'view tasks', 'view pm tasks', 'create tasks', 'delete tasks', 'assign members',
            'update tasks as project manager', 'view pm reports'
        ]);

        $qaRole->syncPermissions([
            'view qa dashboard', 'view projects', 'view tasks', 'update tasks as qa',
            'view own tasks', 'view assigned projects'
        ]);

        $developerLikePermissions = [
            'view developer dashboard', 'view projects', 'view tasks', 'update tasks as developer',
            'view own tasks', 'view assigned projects'
        ];
        $devRole->syncPermissions($developerLikePermissions);
        $designerRole->syncPermissions($developerLikePermissions);
        $analystRole->syncPermissions($developerLikePermissions);
    }
}