<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'division',
        'phone_number',
        'profile_photo_path',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Accessor untuk mendapatkan URL foto profil.
     * Ini menggunakan kolom 'profile_photo_path' dari database Anda.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }
        // Fallback jika tidak ada foto
        return 'https://placehold.co/100x100/ced4da/6c757d?text=' . strtoupper(substr($this->name, 0, 1));
    }

    // ===================================
    // ==         RELATIONSHIPS         ==
    // ===================================

    /**
     * Hubungan User ke Role (Satu User punya satu Role).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi untuk proyek yang dikelola oleh user ini (jika dia seorang PM).
     */
    public function managedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    /**
     * Relasi untuk laporan yang DIBUAT oleh user ini.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'submitted_by_id');
    }

    /**
     * Relasi untuk tugas yang DIBUAT oleh user ini (sebagai PM).
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_by_id');
    }

    /**
     * Relasi untuk tugas yang DITUGASKAN KEPADA user ini.
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    /**
     * Relasi untuk proyek di mana user ini menjadi anggota tim.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot('project_role')->withTimestamps();
    }


    // ===================================
    // ==        HELPER METHODS         ==
    // ===================================

    /**
     * Memeriksa apakah user adalah Admin.
     */
    public function isAdmin()
    {
        // Menambahkan pengecekan 'role' untuk mencegah error jika relasi null
        return $this->role && $this->role->slug === 'admin';
    }

    /**
     * Memeriksa apakah user adalah Project Manager.
     */
    public function isProjectManager()
    {
        return $this->role && $this->role->slug === 'pm';
    }

    /**
     * Memeriksa apakah user adalah Karyawan.
     */
    public function isEmployee()
    {
        return $this->role && $this->role->slug === 'employee';
    }
}
