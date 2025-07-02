<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
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
        return true; // Semua bisa melihat daftar laporan
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        // PM bisa melihat laporan proyeknya
        if ($user->role->slug === 'pm') {
            return $user->id === $report->project->project_manager_id ||
                   $user->id === $report->submitted_by_id;
        }

        // Employee bisa melihat laporan proyek yang dia ikuti
        if ($user->role->slug === 'employee') {
            return $report->project->members->contains($user) ||
                   $user->id === $report->submitted_by_id;
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
    public function update(User $user, Report $report): bool
    {
        // Hanya pembuat laporan yang bisa update, dan hanya jika status masih pending
        return $user->id === $report->submitted_by_id && 
               $report->status === 'Menunggu Persetujuan';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        // Hanya pembuat laporan yang bisa hapus, dan hanya jika status masih pending
        return $user->id === $report->submitted_by_id && 
               $report->status === 'Menunggu Persetujuan';
    }

    /**
     * Determine whether admin can validate reports.
     */
    public function validate(User $user, Report $report): bool
    {
        return $user->role->slug === 'admin' && 
               $report->status === 'Menunggu Persetujuan';
    }
}
