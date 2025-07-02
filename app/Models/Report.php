<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'project_id',
        'submitted_by_id',
        'content',
        'status',
        'validation_notes',
        'validator_id',
        'validated_at'
        
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by_id');
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Menunggu Persetujuan' => 'bg-warning',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function validator(): BelongsTo
    {
        // Hubungan ini merujuk ke model User melalui foreign key 'validator_id'
        return $this->belongsTo(User::class, 'validator_id');
    }
}
