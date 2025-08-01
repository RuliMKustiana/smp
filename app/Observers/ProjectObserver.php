<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\User;
use App\Notifications\NewProjectNotification;
use Illuminate\Support\Facades\Notification;

class ProjectObserver
{
    public function created(Project $project): void
    {
        $usersToNotify = User::whereHas('role', function ($query) {
            $query->whereIn('slug', ['admin', 'project-manager']);
        })->where('id', '!=', $project->project_manager_id)->get();

        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new NewProjectNotification($project));
        }
    }
}