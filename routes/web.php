<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\ReportValidationController;
use App\Http\Controllers\ProjectManager\ProjectController;
use App\Http\Controllers\ProjectManager\TaskController as PMTaskController;
use App\Http\Controllers\ProjectManager\ReportController as PMReportController;
use App\Http\Controllers\TeamMember\TaskController as TeamMemberTaskController;
use App\Http\Controllers\TeamMember\TaskUpdateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

Route::middleware('auth')->group(function () {
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/api', [SearchController::class, 'api'])->name('search.api');
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('divisions', DivisionController::class)->except(['show']);
    Route::get('/reports', [ReportValidationController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportValidationController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/process', [ReportValidationController::class, 'processValidation'])->name('admin.reports.process');
     Route::post('reports/{report}/process', [ReportValidationController::class, 'processValidation'])->name('reports.process');
});

Route::middleware(['auth', 'role:Project Manager'])->prefix('pm')->name('pm.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::patch('/projects/{project}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
    
    Route::prefix('projects/{project}')->group(function () {
        Route::resource('tasks', PMTaskController::class)->except(['index']);
        Route::patch('tasks/{task}/approve', [PMTaskController::class, 'approve'])->name('tasks.approve');
        Route::patch('tasks/{task}/reject', [PMTaskController::class, 'reject'])->name('tasks.reject');
    });
    
    Route::get('/tasks', [PMTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [PMTaskController::class, 'show'])->name('tasks.show');
    Route::resource('reports', PMReportController::class);
});

Route::middleware(['auth', 'role:Developer|QA|UI/UX Designer|Data Analyst|System Analyst'])->prefix('teammember')->name('teammember.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/tasks', [TeamMemberTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TeamMemberTaskController::class, 'show'])->name('tasks.show');
    Route::patch('/tasks/{task}/update-status', [TeamMemberTaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::get('/tasks/{task}/updates/create', [TaskUpdateController::class, 'create'])->name('task-updates.create');
    Route::post('/tasks/{task}/updates', [TaskUpdateController::class, 'store'])->name('task-updates.store');
    Route::get('/tasks/{task}/updates/{update}/edit', [TaskUpdateController::class, 'edit'])->name('task-updates.edit');
    Route::patch('/tasks/{task}/updates/{update}', [TaskUpdateController::class, 'update'])->name('task-updates.update');
    Route::get('/projects', [TeamMemberTaskController::class, 'projects'])->name('projects.index');
    Route::get('/projects/{project}', [TeamMemberTaskController::class, 'showProject'])->name('projects.show');
});

require __DIR__.'/auth.php';