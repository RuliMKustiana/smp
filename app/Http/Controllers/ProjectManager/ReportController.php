<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::whereHas('project', function ($q) {
            $q->where('project_manager_id', Auth::id());
        })->with('project')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }

        $reports = $query->paginate(15);

        return view('pm.reports.index', compact('reports'));
    }

    public function create()
    {
        $projects = Project::where('project_manager_id', Auth::id())
            ->whereIn('status', ['Selesai', 'In Progress'])
            ->get();

        return view('pm.reports.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:progress,weekly,monthly,final', // <-- ATURAN BARU
            'content' => 'required|string|min:50' // Mungkin bisa dikurangi dari 100 agar lebih mudah testing
        ]);

        $project = Project::where('id', $validated['project_id'])
            ->where('project_manager_id', Auth::id())
            ->firstOrFail();

        if ($validated['type'] === 'final') {
            $existingFinalReport = Report::where('project_id', $project->id)->where('type', 'final')->first();
            if ($existingFinalReport) {
                return redirect()->back()
                    ->with('error', 'Laporan final untuk proyek ini sudah ada.')
                    ->withInput();
            }
        }

        Report::create($validated + [
            'submitted_by_id' => Auth::id(),
            'status' => 'Menunggu Persetujuan'
        ]);

        return redirect()->route('pm.reports.index')
            ->with('success', 'Laporan berhasil dibuat dan dikirim untuk validasi.');
    }

    public function show(Report $report)
    {
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }

        $report->load('project');
        return view('pm.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }

        if ($report->status !== 'Menunggu Persetujuan') {
            return redirect()->route('pm.reports.show', $report)
                ->with('error', 'Laporan yang sudah divalidasi tidak dapat diedit.');
        }

        return view('pm.reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }
        if ($report->status !== 'Menunggu Persetujuan') {
            return redirect()->route('pm.reports.show', $report)
                ->with('error', 'Laporan yang sudah divalidasi tidak dapat diedit.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:100'
        ]);

        $report->update($validated);

        return redirect()->route('pm.reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }

        if ($report->status !== 'Menunggu Persetujuan') {
            return redirect()->route('pm.reports.index')
                ->with('error', 'Laporan yang sudah divalidasi tidak dapat dihapus.');
        }

        $report->delete();

        return redirect()->route('pm.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
