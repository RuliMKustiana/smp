<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Notifications\AssignedToProjectNotification;
use Illuminate\Support\Facades\Notification;
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

        $teamMembers = User::role(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])->orderBy('name')->get();

        return view('pm.projects.create', compact('teamMembers'));
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

        if ($request->has('team_members')) {
            $membersToAttach = [];
            foreach ($request->team_members as $memberId) {
                $membersToAttach[$memberId] = ['project_role' => 'Member'];
            }
            $project->members()->attach($membersToAttach);
        }

        return redirect()->route('pm.projects.index')->with('success', 'Proyek berhasil dibuat.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load('members', 'tasks.attachments');
        return view('pm.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $teamMembers = User::role(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])->orderBy('name')->get();
        $team_member_ids = $project->members->pluck('id')->toArray();

        return view('pm.projects.edit', compact('project', 'teamMembers', 'team_member_ids'));
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

        $membersToSync = [];
        if ($request->has('team_members')) {
            foreach ($request->team_members as $memberId) {
                $membersToSync[$memberId] = ['project_role' => 'Member'];
            }
        }

        $syncResult = $project->members()->sync($membersToSync);
        $newlyAddedMemberIds = $syncResult['attached'];

        if (!empty($newlyAddedMemberIds)) {
            $newMembers = User::find($newlyAddedMemberIds);
            Notification::send($newMembers, new AssignedToProjectNotification($project));
        }

        return redirect()->route('pm.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('pm.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }

    public function kanban(Project $project)
    {
        $this->authorize('view', $project);
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