<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Izinkan admin untuk melakukan semua aksi.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role->slug === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->slug === 'pm' || $user->role->slug === 'employee';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // PM dapat melihat semua task dalam proyeknya
        if ($user->role->slug === 'pm') {
            return $user->id === $task->project->project_manager_id;
        }

        // Employee dapat melihat task yang ditugaskan kepadanya atau dalam proyek yang dia ikuti
        if ($user->role->slug === 'employee') {
            return $task->assigned_to_id === $user->id || 
                   $task->project->members->contains($user);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role->slug === 'pm';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // PM dapat update task dalam proyeknya
        if ($user->role->slug === 'pm') {
            return $user->id === $task->project->project_manager_id;
        }

        // Employee dapat update status task yang ditugaskan kepadanya
        if ($user->role->slug === 'employee') {
            return $task->assigned_to_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Hanya PM yang dapat menghapus task
        return $user->role->slug === 'pm' && 
               $user->id === $task->project->project_manager_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
