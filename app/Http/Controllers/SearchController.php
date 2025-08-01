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
        
        $results = !empty($query) ? $this->performSearch($query) : [
            'projects' => collect(),
            'tasks' => collect(),
            'users' => collect()
        ];

        return view('search.index', [
            'query' => $query,
            'results' => $results
        ]);
    }

    public function api(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json(['projects' => [], 'tasks' => [], 'users' => []]);
        }

        $results = $this->performSearch($query);

        return response()->json([
            'projects' => $results['projects']->map(fn ($project) => [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status,
                'url' => $this->getProjectUrl($project)
            ]),
            'tasks' => $results['tasks']->map(fn ($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'project' => $task->project->name,
                'url' => $this->getTaskUrl($task)
            ]),
            'users' => $results['users']->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->first() ?? 'N/A',
                'division' => $user->division?->name ?? 'N/A'
            ])
        ]);
    }

    private function performSearch($query)
    {
        $user = Auth::user();
        
        $projectsQuery = Project::where(fn ($q) => $q->where('name', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%"));
        $tasksQuery = Task::where(fn ($q) => $q->where('title', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%"));
        
        if ($user->hasRole('Project Manager')) {
            $projectsQuery->where('project_manager_id', $user->id);
            $tasksQuery->whereHas('project', fn ($q) => $q->where('project_manager_id', $user->id));
        } elseif ($user->hasRole(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])) {
            $projectsQuery->whereHas('members', fn ($q) => $q->where('users.id', $user->id));
            $tasksQuery->where(fn ($q) => 
                $q->where('assigned_to_id', $user->id)
                  ->orWhereHas('project.members', fn ($subQ) => $subQ->where('users.id', $user->id))
            );
        }

        $users = collect();
        if ($user->can('manage users')) {
            $users = User::where(fn ($q) => 
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
            )->with('roles')->limit(10)->get();
        }

        return [
            'projects' => $projectsQuery->with('projectManager')->limit(10)->get(),
            'tasks' => $tasksQuery->with(['project', 'assignedTo'])->limit(10)->get(),
            'users' => $users
        ];
    }

    private function getProjectUrl($project)
    {
        $user = Auth::user();
        
        if ($user->hasRole('Project Manager')) {
            return route('pm.projects.show', $project);
        } elseif ($user->hasRole(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])) {
            return route('teammember.projects.show', $project);
        }
        
        return '#';
    }

    private function getTaskUrl($task)
    {
        $user = Auth::user();
        
        if ($user->hasRole('Project Manager')) {
            return route('pm.tasks.show', $task);
        } elseif ($user->hasRole(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])) {
            return route('teammember.tasks.show', $task);
        }
        
        return '#';
    }
}