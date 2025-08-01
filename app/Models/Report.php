<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property string|null $type
 * @property int $project_id
 * @property int $submitted_by_id
 * @property string $content
 * @property string $status
 * @property string|null $validation_notes
 * @property int|null $validator_id
 * @property string|null $validated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read mixed $status_badge_class
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $submittedBy
 * @property-read \App\Models\User|null $validator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereSubmittedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereValidatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereValidationNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereValidatorId($value)
 * @mixin \Eloquent
 */
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
