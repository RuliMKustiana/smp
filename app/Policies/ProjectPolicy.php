<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Gunakan hasRole() dari Spatie
        if ($user->hasRole('Admin')) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Cek berdasarkan izin, bukan peran
        return $user->can('view projects');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // PM proyek atau anggota tim bisa melihat detail proyek
        return $user->id === $project->project_manager_id || 
               $project->members->contains($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create projects');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Hanya PM dari proyek tersebut yang boleh update
        return $user->can('edit projects') && $user->id === $project->project_manager_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Hanya PM dari proyek tersebut yang boleh hapus
        return $user->can('delete projects') && $user->id === $project->project_manager_id;
    }
}