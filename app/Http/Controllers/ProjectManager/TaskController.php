<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $query = Task::whereHas('project', function ($q) {
            $q->where('project_manager_id', Auth::id());
        })->with(['project', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }
        
        $orderCase = "CASE
                        WHEN status = 'In Progress' THEN 1
                        WHEN status = 'Belum Dikerjakan' THEN 2
                        WHEN status = 'Revisi' THEN 3
                        WHEN status = 'Blocked' THEN 4
                        WHEN status = 'Selesai' THEN 5
                        ELSE 6
                      END";

        $tasks = $query->orderByRaw($orderCase)
                      ->orderBy('deadline', 'asc')
                      ->paginate(15);

        $projects = Project::where('project_manager_id', Auth::id())->orderBy('name')->get();
        $statuses = ['To-Do', 'In Progress' , 'In Review', 'Completed', 'Blocked'];
        return view('pm.tasks.index', compact('tasks', 'projects', 'statuses'));
    }

    public function create(Project $project)
    {
        $this->authorize('update', $project);
        $employees = $project->members;
        $parentTasks = $project->tasks()->whereNull('parent_task_id')->get();
        return view('pm.tasks.create', compact('project', 'employees', 'parentTasks'));
    }


    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'parent_task_id' => 'nullable|exists:tasks,id',
            'status' => 'required|in:To-Do,In Progress,Selesai,Revisi,Blocked',
            'estimated_hours' => 'nullable|numeric|min:0',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);

        $task = $project->tasks()->create($validated + ['assigned_by_id' => Auth::id()]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('attachments/tasks/' . $task->id, $filename, 'public');

                $task->attachments()->create([
                    'user_id'   => Auth::id(),
                    'file_name' => $filename, 
                    'file_path' => $path,     
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        if (isset($validated['assigned_to_id'])) {
            $assignedUser = User::find($validated['assigned_to_id']);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssigned($task));
            }
        }

        return redirect()->route('pm.projects.show', $project)
            ->with('success', 'Tugas berhasil dibuat dan ditugaskan.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['project', 'assignedTo', 'assignedBy', 'updates.user', 'attachments']);
        return view('pm.tasks.show', compact('task'));
    }

    public function edit(Project $project, Task $task)
    {
        $this->authorize('update', $task);
        $employees = $project->members;
        $parentTasks = $project->tasks()->whereNull('parent_task_id')->where('id', '!=', $task->id)->get();
        return view('pm.tasks.edit', compact('project', 'task', 'employees', 'parentTasks'));
    }


    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'parent_task_id' => 'nullable|exists:tasks,id',
            'status' => 'required|in:To-Do,In Progress,Selesai,Revisi,Blocked',
            'priority' => 'nullable|in:Rendah,Sedang,Tinggi',
            'estimated_hours' => 'nullable|numeric|min:0',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);
        
        $task->update($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('attachments/tasks/' . $task->id, $filename, 'public');

                $task->attachments()->create([
                    'user_id'   => Auth::id(),
                    'file_name' => $filename, 
                    'file_path' => $path, 
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('pm.projects.show', $project)->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);
        Storage::disk('public')->deleteDirectory('attachments/tasks/' . $task->id);
        $task->delete();
        return redirect()->route('pm.projects.show', $project)->with('success', 'Tugas berhasil dihapus.');
    }
}
