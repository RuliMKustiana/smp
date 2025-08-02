<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LaporanDisetujuiMail;

class ReportValidationController extends Controller
{
    public function index(Request $request)
    {
        $pendingStatuses = [Report::STATUS_PENDING];

        $pendingReports = Report::whereIn('status', $pendingStatuses)
            ->with('project', 'submittedBy')
            ->latest()
            ->get();

        $historyStatuses = ['Disetujui', 'Ditolak', 'validated', 'rejected'];

        $validatedReportsQuery = Report::whereIn('status', $historyStatuses)
            ->with('project', 'submittedBy', 'validator');

        if ($request->filled('status') && $request->status !== 'Semua') {
            $statusFilter = $request->status === 'validated' ? 'Disetujui' : 'Ditolak';
            $validatedReportsQuery->where('status', $statusFilter);
        }

        $validatedReports = $validatedReportsQuery->latest()->paginate(10);

        return view('admin.reports.index', compact('pendingReports', 'validatedReports'));
    }

    public function show(Report $report)
    {
        $report->load(['project.tasks.attachments', 'submittedBy', 'validator']);

        return view('admin.reports.show', compact('report'));
    }

    public function processValidation(Request $request, Report $report)
    {
        $validated = $request->validate([
            'action' => 'required|in:validated,rejected',
            'validation_notes' => 'required_if:action,rejected|nullable|string|max:1000',
        ], [
            'validation_notes.required_if' => 'Catatan revisi wajib diisi jika laporan ditolak.'
        ]);

        $report->update([
            'status' => $validated['action'] === 'validated' ? Report::STATUS_VALIDATED : Report::STATUS_REJECTED,
            'validation_notes' => $validated['validation_notes'],
            'validator_id' => Auth::id(),
            'validated_at' => now(),
        ]);

        if ($validated['action'] === 'validated' && $report->type === 'final') {
            if ($report->project) {
                $report->project->update(['status' => 'Selesai']);
            }
            try {
                if ($report->submittedBy && $report->submittedBy->email) {
                    Mail::to($report->submittedBy->email)->send(new LaporanDisetujuiMail($report));
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        $message = $validated['action'] === 'validated'
            ? 'Laporan berhasil disetujui.'
            : 'Laporan berhasil ditolak dan dikembalikan untuk revisi.';

        return redirect()->route('admin.reports.index')->with('success', $message);
    }
}
