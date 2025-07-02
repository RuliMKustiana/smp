<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'deadline_date',
        'project_manager_id',
        'status',
        'priority'
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline_date' => 'date',
    ];

    // Relationships
    public function projectManager() 
    { 
        return $this->belongsTo(User::class, 'project_manager_id'); 
    }
    
    public function tasks()
{
    return $this->hasMany(Task::class);
}
    
    public function reports() 
    { 
        return $this->hasMany(Report::class); 
    }
    
    public function comments() 
    { 
        return $this->morphMany(Comment::class, 'commentable'); 
    }
    
    public function attachments() 
    { 
        return $this->morphMany(Attachment::class, 'attachable'); 
    }

    // Helper methods
    public function getProgressPercentageAttribute()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;
        
        $completedTasks = $this->tasks()->where('status', 'Selesai')->count();
        return round(($completedTasks / $totalTasks) * 100);
    }

    public function getDaysRemainingAttribute()
    {
        return now()->diffInDays($this->deadline_date, false);
    }

    public function isOverdue()
    {
        return $this->deadline_date < now() && $this->status !== 'Selesai';
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user') // Nama tabel pivot
                    ->withPivot('project_role') // Wajib ada untuk bisa akses kolom tambahan
                    ->withTimestamps(); // Rekomendasi untuk mengelola created_at/updated_at di pivot
    }
}
