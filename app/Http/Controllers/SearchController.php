<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return view('search.index', [
                'query' => '',
                'results' => [
                    'projects' => collect(),
                    'tasks' => collect(),
                    'users' => collect()
                ]
            ]);
        }

        $results = $this->performSearch($query);

        return view('search.index', [
            'query' => $query,
            'results' => $results
        ]);
    }

    public function api(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([
                'projects' => [],
                'tasks' => [],
                'users' => []
            ]);
        }

        $results = $this->performSearch($query);

        return response()->json([
            'projects' => $results['projects']->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'status' => $project->status,
                    'url' => $this->getProjectUrl($project)
                ];
            }),
            'tasks' => $results['tasks']->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status,
                    'project' => $task->project->name,
                    'url' => $this->getTaskUrl($task)
                ];
            }),
            'users' => $results['users']->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name,
                    'division' => $user->division
                ];
            })
        ]);
    }

    private function performSearch($query)
    {
        $user = Auth::user();
        
        // Search Projects
        $projectsQuery = Project::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        });

        // Filter berdasarkan role
        if ($user->role->slug === 'pm') {
            $projectsQuery->where('project_manager_id', $user->id);
        } elseif ($user->role->slug === 'employee') {
            $projectsQuery->whereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        $projects = $projectsQuery->with('projectManager')->limit(10)->get();

        // Search Tasks
        $tasksQuery = Task::where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        });

        // Filter berdasarkan role
        if ($user->role->slug === 'pm') {
            $tasksQuery->whereHas('project', function ($q) use ($user) {
                $q->where('project_manager_id', $user->id);
            });
        } elseif ($user->role->slug === 'employee') {
            $tasksQuery->where(function ($q) use ($user) {
                $q->where('assigned_to_id', $user->id)
                  ->orWhereHas('project.members', function ($subQ) use ($user) {
                      $subQ->where('users.id', $user->id);
                  });
            });
        }

        $tasks = $tasksQuery->with(['project', 'assignedTo'])->limit(10)->get();

        // Search Users (hanya admin yang bisa search semua user)
        $users = collect();
        if ($user->role->slug === 'admin') {
            $users = User::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%");
            })->with('role')->limit(10)->get();
        }

        return [
            'projects' => $projects,
            'tasks' => $tasks,
            'users' => $users
        ];
    }

    private function getProjectUrl($project)
    {
        $user = Auth::user();
        
        if ($user->role->slug === 'admin') {
            return '#'; // Admin tidak punya route khusus untuk project
        } elseif ($user->role->slug === 'pm') {
            return route('pm.projects.show', $project);
        } elseif ($user->role->slug === 'employee') {
            return route('employee.projects.show', $project);
        }
        
        return '#';
    }

    private function getTaskUrl($task)
    {
        $user = Auth::user();
        
        if ($user->role->slug === 'admin') {
            return '#'; // Admin tidak punya route khusus untuk task
        } elseif ($user->role->slug === 'pm') {
            return route('pm.tasks.show', $task);
        } elseif ($user->role->slug === 'employee') {
            return route('employee.tasks.show', $task);
        }
        
        return '#';
    }
}
