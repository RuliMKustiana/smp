<?php

namespace App\Http\View\Composers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $taskNotificationCount = 0;

            // Mengganti isDeveloper() dengan can('izin')
            if ($user->can('view assigned tasks')) {
                $taskNotificationCount = Task::where('assigned_to_id', $user->id)
                                            ->whereIn('status', ['To-Do', 'Revisi'])
                                            ->count();
            } 
            elseif ($user->can('view reviewable tasks')) {
                $taskNotificationCount = Task::where('status', 'In Review')
                                            ->whereHas('project.members', function ($query) use ($user) {
                                                $query->where('users.id', $user->id);
                                            })
                                            ->count();
            }

            $view->with('taskNotificationCount', $taskNotificationCount);
        } else {
            $view->with('taskNotificationCount', 0);
        }
    }
}