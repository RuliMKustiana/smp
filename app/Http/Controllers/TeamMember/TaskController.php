<?php

namespace App\Http\Controllers\TeamMember;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $stats = [];
        $statusesForFilter = [];

        if ($user->can('view developer tasks')) {
            $baseQuery = Task::where('assigned_to_id', $user->id);
            $allMyTasks = (clone $baseQuery)->get();

            $stats = [
                'pending'     => $allMyTasks->whereIn('status', ['To-Do', 'Revisi'])->count(),
                'in_progress' => $allMyTasks->where('status', 'In Progress')->count(),
                'in_review'   => $allMyTasks->where('status', 'In Review')->count(),
                'completed'   => $allMyTasks->where('status', 'Completed')->count(),
                'on_hold'     => $allMyTasks->where('status', 'Blocked')->count(),
                'revisi'      => $allMyTasks->where('status', 'Revisi')->count(),
            ];
            
            $statusesForFilter = ['To-Do', 'Revisi', 'In Progress', 'In Review', 'Completed', 'Blocked'];

        } elseif ($user->can('view qa tasks')) {
            $baseQuery = Task::whereHas('project.members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            $allMyTasks = (clone $baseQuery)->get();

            $stats = [
                'in_review'   => $allMyTasks->where('status', 'In Review')->count(),
                'completed'   => $allMyTasks->where('status', 'Completed')->count(),
                'revisi'      => $allMyTasks->where('status', 'Revisi')->count(),
                'on_hold'     => $allMyTasks->where('status', 'Blocked')->count(),
            ];
            
            $statusesForFilter = ['In Review', 'Completed', 'Revisi', 'Blocked'];
        } else {
            $baseQuery = Task::query()->whereRaw('1 = 0');
        }

        $tasksQuery = (clone $baseQuery);
        if ($request->filled('status')) {
            $status = str_replace('-', ' ', $request->status);
            $tasksQuery->where('status', 'like', '%' . $status . '%');
        }

        if ($request->get('sort') == 'due_date') {
            $tasksQuery->orderBy('deadline', 'asc');
        } elseif ($request->get('sort') == 'priority') {
            $tasksQuery->orderByRaw("FIELD(priority, 'Tinggi', 'Sedang', 'Rendah')");
        } else {
            $orderCase = "CASE WHEN status = 'In Progress' THEN 1 WHEN status = 'In Review' THEN 2 WHEN status = 'To-Do' THEN 3 WHEN status = 'Revisi' THEN 4 ELSE 5 END";
            $tasksQuery->orderByRaw($orderCase)->orderBy('deadline', 'asc');
        }

        $tasks = Task::where('assigned_to_id', auth()->id())
            ->when(request('status'), function ($q) {
                $status = request('status');
                // Jika status dari filter pakai slug, konversi ke nama status
                $status = Str::title(str_replace('-', ' ', $status));
                $q->where('status', $status);
            })
            ->orderBy('deadline', 'asc')
            ->paginate(12);

        return view('teammember.tasks.index', compact('tasks', 'stats', 'statusesForFilter'));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['project', 'assignedBy', 'updates.user', 'comments.user', 'attachments']);
        return view('teammember.tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:To-Do,In Progress,In Review,Completed,Revisi,Blocked',
            'note' => 'nullable|string|max:500'
        ]);

        $oldStatus = $task->status;
        $task->update(['status' => $validated['status']]);

        $task->updates()->create([
            'user_id' => Auth::id(),
            'description' => $request->note ?? 'Status diubah dari ' . $oldStatus . ' ke ' . $validated['status'],
            'status_change' => $validated['status']
        ]);

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function projects()
    {
        $projects = Project::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
        })->with(['projectManager', 'tasks' => function ($query) {
            $query->where('assigned_to_id', Auth::id());
        }])->paginate(9);

        return view('teammember.projects.index', compact('projects'));
    }

    public function showProject(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['projectManager', 'members']);

        $myTasks = $project->tasks()
            ->where('assigned_to_id', Auth::id())
            ->latest()
            ->get();

        return view('teammember.projects.show', compact('project', 'myTasks'));
    }
}
