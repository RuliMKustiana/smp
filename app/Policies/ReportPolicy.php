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
        
        if ($user->hasRole('Project Manager')) {
            return $user->id === $report->project->project_manager_id ||
                   $user->id === $report->submitted_by_id;
        }

        if ($user->hasRole(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])) {
            return $report->project->members->contains($user) ||
                   $user->id === $report->submitted_by_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create reports');
    }

    public function update(User $user, Report $report): bool
{
    $editableStatuses = ['Menunggu Persetujuan', 'Ditolak', 'rejected'];

    // dd([
    //     'ID User Login' => $user->id,
    //     'ID Pembuat Laporan' => $report->submitted_by_id,
    //     'Status Laporan' => $report->status,
    //     '---' => '--- HASIL PENGECEKAN ---',
    //     'Punya Izin "edit own reports"?' => $user->can('edit own reports'),
    //     'Apakah Pemilik Laporan?' => $user->id === $report->submitted_by_id,
    //     'Apakah Status Bisa Diedit?' => in_array(trim($report->status), $editableStatuses)
    // ]);

    // Kode asli Anda di bawahnya
    return $user->can('edit own reports') &&
           $user->id === $report->submitted_by_id && 
           in_array($report->status, $editableStatuses);
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