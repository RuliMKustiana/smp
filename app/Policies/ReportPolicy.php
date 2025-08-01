<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // Menggunakan hasRole dari Spatie
        if ($user->hasRole('Admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true; 
    }

    public function view(User $user, Report $report): bool
    {
        // PM bisa melihat laporan di proyeknya atau yang ia buat
        if ($user->hasRole('Project Manager')) {
            return $user->id === $report->project->project_manager_id ||
                   $user->id === $report->submitted_by_id;
        }

        // Member bisa melihat laporan jika mereka anggota proyek atau yang mereka buat
        if ($user->hasRole(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])) {
            return $report->project->members->contains($user) ||
                   $user->id === $report->submitted_by_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Menggunakan izin (permission)
        return $user->can('create reports');
    }

    public function update(User $user, Report $report): bool
    {
        // Menggunakan izin dan logika bisnis
        return $user->can('edit own reports') &&
               $user->id === $report->submitted_by_id && 
               $report->status === 'Menunggu Persetujuan';
    }

    public function delete(User $user, Report $report): bool
    {
        // Menggunakan izin dan logika bisnis
        return $user->can('delete own reports') &&
               $user->id === $report->submitted_by_id && 
               $report->status === 'Menunggu Persetujuan';
    }

    public function validate(User $user, Report $report): bool
    {
        // Menggunakan izin
        return $user->can('validate reports');
    }
}