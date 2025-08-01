<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->can('view admin dashboard')) {
            return $this->adminDashboard();
        } elseif ($user->can('view pm dashboard')) {
            return $this->pmDashboard();
        } elseif ($user->can('view developer dashboard') || $user->can('view qa dashboard')) {
            return $this->teammemberDashboard();
        }
        
        Auth::logout();
        return redirect('/login')->withErrors('Anda tidak memiliki izin untuk mengakses dashboard manapun.');
    }

    private function adminDashboard()
    {
        $data = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'In Progress')->count(),
            'pending_reports_count' => Report::where('status', 'Menunggu Persetujuan')->count(),
            'pending_reports' => Report::with(['project', 'submittedBy'])
                ->where('status', 'Menunggu Persetujuan')
                ->latest()
                ->take(5)
                ->get(),
        ];
        return view('admin.dashboard', $data);
    }

    private function pmDashboard()
    {
        $pmId = Auth::id();

        $total_projects = Project::where('project_manager_id', $pmId)->count();
        $active_projects = Project::where('project_manager_id', $pmId)->where('status', 'In Progress')->count();
        $total_tasks = Task::whereHas('project', fn($q) => $q->where('project_manager_id', $pmId))->count();
        $overdue_tasks = Task::whereHas('project', fn($q) => $q->where('project_manager_id', $pmId))
            ->where('deadline', '<', now()->startOfDay())
            ->whereNotIn('status', ['Completed'])
            ->count();

        $projectStatusData = Project::where('project_manager_id', $pmId)
            ->groupBy('status')
            ->select('status', DB::raw('count(*) as total'))
            ->pluck('total', 'status');

        $project_status_labels = $projectStatusData->keys();
        $project_status_values = $projectStatusData->values();

        $projects_overview = Project::where('project_manager_id', $pmId)
            ->with(['members'])
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'Completed');
            }])
            ->latest()
            ->take(5)
            ->get();

        $tasks_near_deadline = Task::whereHas('project', fn($q) => $q->where('project_manager_id', $pmId))
            ->with('project')
            ->where('status', '!=', 'Completed')
            ->where('deadline', '>=', now()->startOfDay())
            ->where('deadline', '<=', now()->addDays(7)->endOfDay())
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();

        return view('pm.dashboard', compact(
            'total_projects',
            'active_projects',
            'total_tasks',
            'overdue_tasks',
            'project_status_labels',
            'project_status_values',
            'projects_overview',
            'tasks_near_deadline'
        ));
    }

    public function teammemberDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $stats = [];
        $tasks = collect();

        if ($user->can('view developer dashboard')) {
            $baseQuery = Task::where('assigned_to_id', $user->id);
            $allMyTasks = (clone $baseQuery)->get();
            $stats = [
                'in_review' => $allMyTasks->where('status', 'In Review')->count(),
                'to_do'     => $allMyTasks->where('status', 'To-Do')->count(),
                'revisi'    => $allMyTasks->where('status', 'Revisi')->count(),
                'completed' => $allMyTasks->where('status', 'Completed')->count(),
            ];
            $tasks = (clone $baseQuery)->where('status', '!=', 'Completed')
                ->latest('updated_at')
                ->take(6)
                ->get();
        } elseif ($user->can('view qa dashboard')) {
            $baseQuery = Task::whereHas('project.members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            $allMyTasks = (clone $baseQuery)->get();
            $stats = [
                'pending'     => $allMyTasks->whereIn('status', ['To-Do', 'Revisi'])->count(),
                'in_progress' => $allMyTasks->where('status', 'In Progress')->count(),
                'completed'   => $allMyTasks->where('status', 'Completed')->count(),
                'on_hold'     => $allMyTasks->where('status', 'Blocked')->count(),
                'in_review'   => $allMyTasks->where('status', 'In Review')->count(),
            ];
            $tasks = (clone $baseQuery)->where('status', 'In Review')
                ->latest('updated_at')
                ->take(6)
                ->get();
        }

        return view('teammember.dashboard', compact('stats', 'tasks'));
    }
}