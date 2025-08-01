<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property string $description
 * @property string|null $link
 * @property string|null $status_change
 * @property numeric|null $hours_spent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Task $task
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereHoursSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereStatusChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskUpdate whereUserId($value)
 * @mixin \Eloquent
 */
class TaskUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'description',
        'status_change',
        'hours_spent',
        'link',
    ];

    protected $casts = [
        'hours_spent' => 'decimal:2',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
