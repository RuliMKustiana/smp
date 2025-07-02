<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
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
     * Tentukan apakah user bisa melihat daftar proyek.
     * Hanya PM yang bisa melihat proyek yang mereka kelola.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->slug === 'pm';
    }

    /**
     * Tentukan apakah user bisa melihat detail proyek.
     * Hanya PM dari proyek tsb atau anggota tim yang bisa melihat.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->project_manager_id || 
               $project->members->contains($user);
    }

    /**
     * Tentukan apakah user bisa membuat proyek baru.
     */
    public function create(User $user): bool
    {
        return $user->role->slug === 'pm';
    }

    /**
     * Tentukan apakah user bisa mengupdate proyek.
     * Hanya PM dari proyek tsb yang bisa update.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->project_manager_id;
    }

    /**
     * Tentukan apakah user bisa menghapus proyek.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->project_manager_id;
    }
}