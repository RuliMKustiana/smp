@extends('layouts.app')

@section('title', 'Dashboard Member')

@section('header')
    <h1 class="h2">Dashboard Member</h1>
    <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</p>
@endsection

@section('content')
    <div class="row mb-4">
        @can('view developer dashboard')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Tugas Direview</p>
                            <h4 class="stat-card-value">{{ $stats['in_review'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-dark">
                            <i class="fas fa-spinner fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Menunggu Dikerjakan</p>
                            <h4 class="stat-card-value">{{ $stats['to_do'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-yellow">
                            <i class="fas fa-pause-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Perlu Revisi</p>
                            <h4 class="stat-card-value">{{ $stats['revisi'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-red">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Tugas Selesai</p>
                            <h4 class="stat-card-value">{{ $stats['completed'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-green">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('view qa dashboard')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Siap Direview</p>
                            <h4 class="stat-card-value">{{ $stats['in_review'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-blue">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Perlu Revisi</p>
                            <h4 class="stat-card-value">{{ $stats['pending'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-red">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Tugas Selesai</p>
                            <h4 class="stat-card-value">{{ $stats['completed'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-green">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Tugas Diblokir</p>
                            <h4 class="stat-card-value">{{ $stats['on_hold'] ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-dark">
                            <i class="fas fa-ban fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <div class="row mt-4">
        @forelse($tasks as $task)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card h-100 task-card">
                    <div class="card-body p-3 d-flex flex-column">
                        @php
                            $iconClass = 'fa-tasks';
                            $iconColor = 'bg-gradient-green';
                            $projectNameFirstWord = strtok($task->project->name, ' ');
                            if (stripos($projectNameFirstWord, 'App') !== false) {
                                $iconClass = 'fa-mobile-alt';
                                $iconColor = 'bg-gradient-danger';
                            } elseif (stripos($projectNameFirstWord, 'Web') !== false) {
                                $iconClass = 'fa-code';
                                $iconColor = 'bg-gradient-info';
                            } elseif (stripos($projectNameFirstWord, 'Landing') !== false) {
                                $iconClass = 'fa-file-alt';
                                $iconColor = 'bg-gradient-success';
                            }
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon icon-shape icon-sm shadow-sm {{ $iconColor }} text-center rounded-circle me-3">
                                <i class="fas {{ $iconClass }} text-white"></i>
                            </div>
                            <div>
                                <h6 class="card-title fw-bold mb-0">
                                    <a href="{{ route('teammember.tasks.show', $task) }}" class="text-dark text-decoration-none">
                                        {{ $task->title }}
                                    </a>
                                </h6>
                                <p class="text-sm text-muted mb-0">
                                    <a href="{{ route('teammember.projects.show', $task->project) }}" class="text-muted text-decoration-none">
                                        {{ $task->project->name }}
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center text-sm text-muted mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span>
                                @if($task->deadline)
                                    @if ($task->isOverdue())
                                        <span class="text-danger fw-bold">Terlambat {{ $task->deadline->diffForHumans() }}</span>
                                    @else
                                        Sisa waktu {{ $task->deadline->diffForHumans(null, true) }}
                                    @endif
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-sm fw-bold">Team Member</span>
                                <span class="text-sm fw-bold">Progress</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="avatar-group">
                                    @foreach ($task->project->members->take(3) as $member)
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $member->name }}">
                                            <img src="{{ $member->profile_photo_url ?? 'https://placehold.co/40x40/EFEFEF/333333?text=' . substr($member->name, 0, 1) }}" alt="{{ $member->name }}">
                                        </a>
                                    @endforeach
                                    @if ($task->project->members->count() > 3)
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="+{{ $task->project->members->count() - 3 }} lainnya">
                                            <div class="bg-light text-dark d-flex align-items-center justify-content-center h-100">
                                                <small>+{{ $task->project->members->count() - 3 }}</small>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                                @php
                                    $progress = 0;
                                    $taskStatus = strtolower($task->status);
                                    if ($taskStatus === 'completed' || $taskStatus === 'selesai') {
                                        $progress = 100;
                                    } elseif ($taskStatus === 'in_progress' || $taskStatus === 'in progress') {
                                        $progress = 50;
                                    } elseif ($taskStatus === 'in_review' || $taskStatus === 'in review') {
                                        $progress = 75;
                                    }
                                @endphp
                                <span class="text-sm fw-bold">{{ $progress }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Tugas</h5>
                        <p class="text-muted">Semua tugas Anda sudah selesai atau belum ada tugas baru.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator && $tasks->count() > 0)
        <div class="d-flex justify-content-center mt-4">
            {{ $tasks->appends(request()->query())->links() }}
        </div>
    @endif
@endsection

@push('styles')
<style>
.task-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.task-card:hover {
    transform: translateY(-px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.06);
}
.avatar-group .avatar {
    margin-left: -8px;
    border: 2px solid #fff;
    transition: transform 0.2s ease;
}
.avatar-group .avatar:hover {
    transform: scale(1.1) translateY(-2px);
    z-index: 10;
}
.stretched-link::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    content: "";
}
</style>
@endpush