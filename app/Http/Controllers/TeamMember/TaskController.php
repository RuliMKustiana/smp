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
        $baseQuery = Task::query(); // Inisialisasi baseQuery

        if ($user->can('view developer tasks')) {
            // Untuk Developer: Ambil tugas yang di-assign ke dia
            $baseQuery = Task::where('assigned_to_id', $user->id);
            $allMyTasks = (clone $baseQuery)->get();

            $stats = [
                'pending'     => $allMyTasks->whereIn('status', ['To-Do', 'Revisi'])->count(),
                'in_progress' => $allMyTasks->where('status', 'In Progress')->count(),
                'in_review'   => $allMyTasks->where('status', 'In Review')->count(),
                'completed'   => $allMyTasks->where('status', 'Completed')->count(),
            ];

            $statusesForFilter = ['To-Do', 'Revisi', 'In Progress', 'In Review', 'Completed', 'Blocked'];
        } elseif ($user->can('view qa tasks')) {
            // Untuk QA: Ambil tugas berstatus 'In Review' dari proyek di mana dia menjadi anggota
            $baseQuery = Task::where('status', 'In Review')
                ->whereHas('project.members', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });

            // Statistik untuk QA disesuaikan agar hanya menampilkan tugas yang siap direview
            $stats = [
                'in_review'   => (clone $baseQuery)->count(),
            ];

            // Filter yang relevan untuk QA
            $statusesForFilter = ['In Review', 'Completed', 'Revisi'];
        } else {
            // Jika tidak punya izin, jangan tampilkan apa-apa
            $baseQuery->whereRaw('1 = 0');
        }

        // --- LOGIKA FILTER DAN SORTING ---
        // Logika ini sekarang akan berfungsi karena kita menggunakan $tasksQuery di akhir
        $tasksQuery = (clone $baseQuery);
        if ($request->filled('status')) {
            $status = str_replace('-', ' ', $request->status);
            // Gunakan where() yang lebih ketat daripada like() untuk filter status
            $tasksQuery->where('status', $status);
        }

        if ($request->get('sort') == 'due_date') {
            $tasksQuery->orderBy('deadline', 'asc');
        } elseif ($request->get('sort') == 'priority') {
            $tasksQuery->orderByRaw("FIELD(priority, 'Tinggi', 'Sedang', 'Rendah')");
        } else {
            $orderCase = "CASE WHEN status = 'In Progress' THEN 1 WHEN status = 'In Review' THEN 2 WHEN status = 'To-Do' THEN 3 WHEN status = 'Revisi' THEN 4 ELSE 5 END";
            $tasksQuery->orderByRaw($orderCase)->orderBy('deadline', 'asc');
        }

        $tasks = $tasksQuery->paginate(12);

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
