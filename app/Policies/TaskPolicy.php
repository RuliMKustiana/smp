<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Project Manager') || $user->can('view tasks');
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->id === $task->assigned_to_id) {
            return true;
        }

        if ($user->id === $task->project->project_manager_id) {
            return true;
        }

        if ($user->hasRole('QA')) {
        return $task->project->members->contains($user->id);
    }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create tasks');
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->can('update tasks as project manager')) {
            return $user->id === $task->project->project_manager_id;
        }

        if ($user->can('update tasks as developer')) {
            return $task->assigned_to_id === $user->id &&
                in_array($task->status, ['To-Do', 'In Progress', 'Revisi', 'In Review']);
        }

        if ($user->can('update tasks as qa')) {
            return $task->project->members->contains($user) &&
                $task->status === 'In Review';
        }

        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('delete tasks') && $user->id === $task->project->project_manager_id;
    }

    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }

    public function viewAssignedTasks(User $user): bool
    {
        return $user->can('view assigned tasks');
    }

    public function viewOwnTasks(User $user): bool
    {
        return $user->can('view own tasks');
    }
}
