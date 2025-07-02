<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Project::class);
        $projects = Project::where('project_manager_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('pm.projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        $employees = User::whereHas('role', function ($query) {
            $query->where('slug', 'employee');
        })->get();
        return view('pm.projects.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'deadline_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Belum Dimulai,In Progress,Selesai,Revisi,Dibatalkan',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);

        $project = Project::create($validated + [
            'project_manager_id' => Auth::id()
        ]);
        
        // --- PERBAIKAN DIMULAI DI SINI ---
        if ($request->has('team_members')) {
            $membersToAttach = [];
            foreach ($request->team_members as $memberId) {
                // Siapkan data pivot dengan role default 'Member'
                $membersToAttach[$memberId] = ['project_role' => 'Member'];
            }
            // Attach anggota tim dengan data pivot yang sudah diformat
            $project->members()->attach($membersToAttach);
        }
        // --- PERBAIKAN SELESAI ---

        return redirect()->route('pm.projects.index')->with('success', 'Proyek berhasil dibuat.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load('members');
        return view('pm.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $employees = User::whereHas('role', function ($query) {
            $query->where('slug', 'employee');
        })->get();
        $team_member_ids = $project->members->pluck('id')->toArray();
        return view('pm.projects.edit', compact('project', 'employees', 'team_member_ids'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'deadline_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Belum Dimulai,In Progress,Selesai,Revisi,Dibatalkan',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);
        
        $project->update($validated);
        
        // --- PERBAIKAN DIMULAI DI SINI ---
        $membersToSync = [];
        if ($request->has('team_members')) {
            foreach ($request->team_members as $memberId) {
                // Siapkan data pivot dengan role default 'Member'
                $membersToSync[$memberId] = ['project_role' => 'Member'];
            }
        }
        // Sync akan menghapus relasi lama dan menambahkan yang baru dengan data pivot
        $project->members()->sync($membersToSync);
        // --- PERBAIKAN SELESAI ---

        return redirect()->route('pm.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('pm.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }

    // ... method lainnya tidak berubah ...
    public function kanban(Project $project)
    {
        $this->authorize('view', $project);
        // Tidak perlu eager loading di sini. Cukup kirimkan objek project.
        return view('pm.projects.kanban', compact('project'));
    }

    public function updateStatus(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'status' => 'required|in:Belum Dimulai,In Progress,Selesai,Revisi,Dibatalkan'
        ]);

        $project->update($validated);

        return response()->json(['success' => true]);
    }
}