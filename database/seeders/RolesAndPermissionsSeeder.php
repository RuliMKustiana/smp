<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Permissions ---
        Permission::firstOrCreate(['name' => 'view admin dashboard']);
        Permission::firstOrCreate(['name' => 'view pm dashboard']);
        Permission::firstOrCreate(['name' => 'view developer dashboard']);
        Permission::firstOrCreate(['name' => 'view qa dashboard']);
        Permission::firstOrCreate(['name' => 'view designer dashboard']);
        Permission::firstOrCreate(['name' => 'view analyst dashboard']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'view tasks']);
        Permission::firstOrCreate(['name' => 'view task updates']);
        Permission::firstOrCreate(['name' => 'view task comments']);
        Permission::firstOrCreate(['name' => 'view task attachments']);
        Permission::firstOrCreate(['name' => 'view task history']);
        Permission::firstOrCreate(['name' => 'view task statistics']);
        Permission::firstOrCreate(['name' => 'view task details']);
        Permission::firstOrCreate(['name' => 'view task filters']);
        Permission::firstOrCreate(['name' => 'view task search']);
        Permission::firstOrCreate(['name' => 'view task sorting']);
        Permission::firstOrCreate(['name' => 'view task labels']);
        Permission::firstOrCreate(['name' => 'view task priorities']);
        Permission::firstOrCreate(['name' => 'view task assignees']);
        Permission::firstOrCreate(['name' => 'view task statuses']);
        Permission::firstOrCreate(['name' => 'view task categories']);
        Permission::firstOrCreate(['name' => 'view task tags']);
        Permission::firstOrCreate(['name' => 'view task deadlines']);
        Permission::firstOrCreate(['name' => 'view task dependencies']);
        Permission::firstOrCreate(['name' => 'view task notifications']);
        Permission::firstOrCreate(['name' => 'view task reminders']);
        Permission::firstOrCreate(['name' => 'view task history']);
        Permission::firstOrCreate(['name' => 'view task comments']);
        Permission::firstOrCreate(['name' => 'view task attachments']);
        Permission::firstOrCreate(['name' => 'view task labels']);
        Permission::firstOrCreate(['name' => 'view task priorities']);
        Permission::firstOrCreate(['name' => 'view task assignees']);
        Permission::firstOrCreate(['name' => 'view task statuses']);
        Permission::firstOrCreate(['name' => 'view task categories']);
        Permission::firstOrCreate(['name' => 'view task tags']);
        Permission::firstOrCreate(['name' => 'view task deadlines']);
        Permission::firstOrCreate(['name' => 'view task dependencies']);
        Permission::firstOrCreate(['name' => 'view task notifications']);
        Permission::firstOrCreate(['name' => 'view task reminders']);
        Permission::firstOrCreate(['name' => 'view task history']);
        Permission::firstOrCreate(['name' => 'view task comments']);
        Permission::firstOrCreate(['name' => 'view task attachments']);
        Permission::firstOrCreate(['name' => 'view task labels']);
        Permission::firstOrCreate(['name' => 'view task priorities']);
        Permission::firstOrCreate(['name' => 'view task assignees']);
        Permission::firstOrCreate(['name' => 'view task statuses']);
        Permission::firstOrCreate(['name' => 'view task categories']);
        Permission::firstOrCreate(['name' => 'view task tags']);
        Permission::firstOrCreate(['name' => 'view task deadlines']);
        Permission::firstOrCreate(['name' => 'view task dependencies']);
        Permission::firstOrCreate(['name' => 'view task notifications']);
        Permission::firstOrCreate(['name' => 'view task reminders']);
        Permission::firstOrCreate(['name' => 'view task history']);
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'view user roles']);
        Permission::firstOrCreate(['name' => 'view user permissions']);
        Permission::firstOrCreate(['name' => 'view user divisions']);
        Permission::firstOrCreate(['name' => 'view user activity logs']);
        Permission::firstOrCreate(['name' => 'view user profiles']);
        Permission::firstOrCreate(['name' => 'view user settings']);
        Permission::firstOrCreate(['name' => 'view user notifications']);
        Permission::firstOrCreate(['name' => 'view user preferences']);
        Permission::firstOrCreate(['name' => 'view user activity']);
        Permission::firstOrCreate(['name' => 'view user roles']);
        Permission::firstOrCreate(['name' => 'view user permissions']);
        Permission::firstOrCreate(['name' => 'view user divisions']);
        Permission::firstOrCreate(['name' => 'view user activity logs']);
        Permission::firstOrCreate(['name' => 'view user profiles']);
        Permission::firstOrCreate(['name' => 'view user settings']);
        Permission::firstOrCreate(['name' => 'view user notifications']);
        Permission::firstOrCreate(['name' => 'view user preferences']);
        Permission::firstOrCreate(['name' => 'view user activity']);
        Permission::firstOrCreate(['name' => 'view roles']);
        Permission::firstOrCreate(['name' => 'view divisions']);
        Permission::firstOrCreate(['name' => 'validate reports']);
        Permission::firstOrCreate(['name' => 'view projects']);
        Permission::firstOrCreate(['name' => 'view pm tasks']);
        Permission::firstOrCreate(['name' => 'view pm reports']);
        Permission::firstOrCreate(['name' => 'view own tasks']);
        Permission::firstOrCreate(['name' => 'view assigned tasks']);
        Permission::firstOrCreate(['name' => 'view assigned projects']);
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
        Permission::firstOrCreate(['name' => 'create tasks']);
        Permission::firstOrCreate(['name' => 'delete tasks']);
        Permission::firstOrCreate(['name' => 'update tasks as developer']);
        Permission::firstOrCreate(['name' => 'update tasks as qa']);
        Permission::firstOrCreate(['name' => 'update tasks as designer']);
        Permission::firstOrCreate(['name' => 'update tasks as analyst']);
        Permission::firstOrCreate(['name' => 'update tasks as project manager']);
        Permission::firstOrCreate(['name' => 'view developer tasks']);
        Permission::firstOrCreate(['name' => 'view qa tasks']);
        Permission::firstOrCreate(['name' => 'edit own reports']);
        Permission::firstOrCreate(['name' => 'delete own reports']);
        Permission::firstOrCreate(['name' => 'create reports']);
        Permission::firstOrCreate(['name' => 'delete reports']);
        Permission::firstOrCreate(['name' => 'validate reports']);

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
            'view pm dashboard',
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'view pm tasks',
            'create tasks',
            'delete tasks',
            'assign members',
            'update tasks as project manager',
            'view pm reports'
        ]);

        $qaRole->syncPermissions([
            'view qa dashboard',
            'view projects',
            'update tasks as qa',
            'view own tasks',
            'view assigned projects'
        ]);

        $developerLikePermissions = [
            'view developer dashboard',
            'view projects',
            'update tasks as developer',
            'view own tasks',
            'view assigned projects'
        ];
        $devRole->syncPermissions($developerLikePermissions);
        $designerRole->syncPermissions($developerLikePermissions);
        $analystRole->syncPermissions($developerLikePermissions);
    }
}
