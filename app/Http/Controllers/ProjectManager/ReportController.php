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
        // Query dasar: hanya mengambil laporan dari proyek milik PM yang sedang login
        $query = Report::whereHas('project', function ($q) {
            $q->where('project_manager_id', Auth::id());
        })->with('project')->latest();

        // Terapkan filter STATUS jika ada di URL dan tidak kosong
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Terapkan filter TIPE jika ada di URL dan tidak kosong
        if ($request->filled('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }

        // Eksekusi query dengan paginasi setelah semua filter diterapkan
        $reports = $query->paginate(15);

        // Kirim data ke view
        return view('pm.reports.index', compact('reports'));
    }

    public function create()
    {
        // Hanya proyek yang sudah selesai atau dalam tahap akhir yang bisa dibuat laporannya
        $projects = Project::where('project_manager_id', Auth::id())
            ->whereIn('status', ['Selesai', 'In Progress'])
            ->get();

        return view('pm.reports.create', compact('projects'));
    }

    public function store(Request $request)
    {
        // 1. TAMBAHKAN VALIDASI UNTUK 'type'
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:progress,weekly,monthly,final', // <-- ATURAN BARU
            'content' => 'required|string|min:50' // Mungkin bisa dikurangi dari 100 agar lebih mudah testing
        ]);

        // Pastikan project adalah milik PM yang login
        $project = Project::where('id', $validated['project_id'])
            ->where('project_manager_id', Auth::id())
            ->firstOrFail();

        // Cek apakah sudah ada laporan final untuk proyek ini (jika tipe final)
        if ($validated['type'] === 'final') {
            $existingFinalReport = Report::where('project_id', $project->id)->where('type', 'final')->first();
            if ($existingFinalReport) {
                return redirect()->back()
                    ->with('error', 'Laporan final untuk proyek ini sudah ada.')
                    ->withInput();
            }
        }

        // 2. SIMPAN LAPORAN
        // Karena 'type' sudah ada di dalam array $validated,
        // maka akan otomatis tersimpan ke database.
        Report::create($validated + [
            'submitted_by_id' => Auth::id(),
            'status' => 'Menunggu Persetujuan'
        ]);

        return redirect()->route('pm.reports.index')
            ->with('success', 'Laporan berhasil dibuat dan dikirim untuk validasi.');
    }

    public function show(Report $report)
    {
        // Pastikan laporan adalah milik PM yang login
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }

        $report->load('project');
        return view('pm.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        // Pastikan laporan adalah milik PM yang login
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa edit jika status masih 'Menunggu Persetujuan'
        if ($report->status !== 'Menunggu Persetujuan') {
            return redirect()->route('pm.reports.show', $report)
                ->with('error', 'Laporan yang sudah divalidasi tidak dapat diedit.');
        }

        return view('pm.reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        // Pastikan laporan adalah milik PM yang login
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa edit jika status masih 'Menunggu Persetujuan'
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
        // Pastikan laporan adalah milik PM yang login
        if ($report->project->project_manager_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa hapus jika status masih 'Menunggu Persetujuan'
        if ($report->status !== 'Menunggu Persetujuan') {
            return redirect()->route('pm.reports.index')
                ->with('error', 'Laporan yang sudah divalidasi tidak dapat dihapus.');
        }

        $report->delete();

        return redirect()->route('pm.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
