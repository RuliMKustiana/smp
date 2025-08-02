<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;
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
        $this->authorize('create', Report::class);
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
            'type' => 'required|string|in:progress,weekly,monthly,final',
            'content' => 'required|string|min:50',
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
            'status' => Report::STATUS_PENDING,
            'project_id' => $project->id,
            'validated_at' => null,
            'validation_notes' => null,
            'validator_id' => null,
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

        $editableStatuses = ['Menunggu Persetujuan', 'Ditolak', 'rejected'];
        if (!in_array($report->status, $editableStatuses)) {
            return redirect()->route('pm.reports.show', $report)
                ->with('error', 'Laporan yang sudah disetujui tidak dapat diedit.');
        }

        return view('pm.reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if (in_array($report->status, ['Ditolak', 'rejected'])) {
            $validated['status'] = Report::STATUS_PENDING;
            $validated['validation_notes'] = null;
            $validated['validator_id'] = null;
            $validated['validated_at'] = null;
        }

        $report->update($validated);

        return redirect()->route('pm.reports.index')->with('success', 'Laporan berhasil diperbarui dan dikirim ulang untuk validasi.');
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

    public function before(User $user, string $ability): bool|null
    {
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

    // public function create(User $user): bool
    // {
    //     $this->authorize('create', Report::class);
    //     return $user->can('create reports');
    // }

    public function delete(User $user, Report $report): bool
    {
        return $user->can('delete own reports') &&
            $user->id === $report->submitted_by_id &&
            $report->status === 'Menunggu Persetujuan';
    }

    public function validate(User $user, Report $report): bool
    {
        return $user->can('validate reports');
    }

    
}
