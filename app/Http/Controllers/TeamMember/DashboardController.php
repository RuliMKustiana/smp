<?php

namespace App\Http\Controllers\TeamMember;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $my_tasks = Task::where('assigned_to_id', Auth::id())
    ->orderByRaw("FIELD(status, 'In Progress', 'Belum Dikerjakan', 'Revisi', 'Blocked', 'Selesai')") // Ini penyebab error
    ->orderBy('deadline', 'asc')
    ->get();
        
        // Task statistics
        $taskStats = [
            'total' => $myTasks->count(),
            'pending' => $myTasks->where('status', 'pending')->count(),
            'in_progress' => $myTasks->where('status', 'in_progress')->count(),
            'completed' => $myTasks->where('status', 'completed')->count(),
            'overdue' => $myTasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count()
        ];
        
        $recentUpdates = TaskUpdate::whereHas('task', function($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
            ->with(['task.project'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        
        $myProjects = Project::whereHas('tasks', function($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
            ->withCount(['tasks' => function($query) use ($user) {
                $query->where('assigned_to', $user->id);
            }])
            ->get();
        
        // Upcoming deadlines (next 7 days)
        $upcomingDeadlines = Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'completed')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Work hours this week
        $thisWeekHours = TaskUpdate::whereHas('task', function($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('hours_spent');
        
        
        $dailyCompletion = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $completed = Task::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->count();
            
            $dailyCompletion->push([
                'date' => $date->format('M d'),
                'completed' => $completed
            ]);
        }
        
        return view('teammember.dashboard', compact(
            'taskStats',
            'myTasks',
            'recentUpdates',
            'myProjects',
            'upcomingDeadlines',
            'thisWeekHours',
            'dailyCompletion'
        ));
    }
}
