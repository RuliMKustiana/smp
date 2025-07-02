<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ReportValidationController;
use App\Http\Controllers\ProjectManager\ProjectController;
use App\Http\Controllers\ProjectManager\TaskController as PMTaskController;
use App\Http\Controllers\ProjectManager\ReportController as PMReportController;
use App\Http\Controllers\Employee\TaskController as EmployeeTaskController;
use App\Http\Controllers\Employee\TaskUpdateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\LaporanDisetujuiMail;
use App\Models\Report;
use App\Http\Controllers\CommentController;


Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard route dengan redirect berdasarkan role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

// Global search
Route::middleware('auth')->group(function () {
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/api', [SearchController::class, 'api'])->name('search.api');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // User management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Role management
    Route::resource('roles', RoleController::class)->except(['show']);
    
    // Report validation
    Route::get('/reports', [ReportValidationController::class, 'index'])->name('reports.index');
    
    // Route ini menampilkan halaman form validasi
    Route::get('/reports/{report}', [ReportValidationController::class, 'show'])->name('reports.show');
    
    // Route ini untuk memproses aksi 'Validasi' dari form
    Route::post('/reports/{report}/validate', [ReportValidationController::class, 'validateReport'])->name('reports.validate');
    
    // Route ini untuk memproses aksi 'Tolak' dari form
    Route::post('/reports/{report}/reject', [ReportValidationController::class, 'rejectReport'])->name('reports.reject');
    
});

// Project Manager routes
Route::middleware(['auth', 'pm'])->prefix('pm')->name('pm.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'projectManager'])->name('dashboard');
    
    // Project management
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::patch('/projects/{project}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
    
    // Task management within projects
    Route::prefix('projects/{project}')->group(function () {
        Route::resource('tasks', PMTaskController::class)->except(['index']);
        Route::patch('tasks/{task}/approve', [PMTaskController::class, 'approve'])->name('tasks.approve');
        Route::patch('tasks/{task}/reject', [PMTaskController::class, 'reject'])->name('tasks.reject');
    });
    
    // All tasks view
    Route::get('/tasks', [PMTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [PMTaskController::class, 'show'])->name('tasks.show');
    
    // Report management
    Route::resource('reports', PMReportController::class);
});

// Employee routes
Route::middleware(['auth', 'employee'])->prefix('employee')->name('employee.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'employee'])->name('dashboard');
    
    // My tasks
    Route::get('/tasks', [EmployeeTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [EmployeeTaskController::class, 'show'])->name('tasks.show');
    Route::patch('/tasks/{task}/update-status', [EmployeeTaskController::class, 'updateStatus'])->name('tasks.update-status');
    
    // Task updates
    Route::get('/tasks/{task}/updates/create', [TaskUpdateController::class, 'create'])->name('task-updates.create');

    Route::post('/tasks/{task}/updates', [TaskUpdateController::class, 'store'])->name('task-updates.store');
    Route::get('/tasks/{task}/updates/{update}/edit', [TaskUpdateController::class, 'edit'])->name('task-updates.edit');
    Route::patch('/tasks/{task}/updates/{update}', [TaskUpdateController::class, 'update'])->name('task-updates.update');
    
    // My projects (as team member)
    Route::get('/projects', [EmployeeTaskController::class, 'projects'])->name('projects.index');
    Route::get('/projects/{project}', [EmployeeTaskController::class, 'showProject'])->name('projects.show');
});

require __DIR__.'/auth.php';