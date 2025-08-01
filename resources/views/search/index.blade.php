@extends('layouts.app')

@section('title', 'Pencarian Global')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Pencarian Global</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <form method="GET" action="{{ route('search.index') }}">
                <div class="input-group input-group-lg">
                    <input type="text" 
                           class="form-control" 
                           name="q" 
                           value="{{ $query }}" 
                           placeholder="Cari proyek, tugas, atau pengguna..."
                           autofocus>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($query))
        <div class="row">
            @can('view projects')
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-project-diagram me-2"></i>
                            Proyek ({{ $results['projects']->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($results['projects'] as $project)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">
                                    @php
                                        $projectUrl = '#';
                                        if (auth()->user()->hasRole('Project Manager')) {
                                            $projectUrl = route('pm.projects.show', $project);
                                        } elseif (auth()->user()->hasRole(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])) {
                                            $projectUrl = route('teammember.projects.show', $project);
                                        }
                                    @endphp
                                    <a href="{{ $projectUrl }}" class="text-decoration-none">
                                        {{ $project->name }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-1">{{ Str::limit($project->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-{{ $project->status === 'Completed' ? 'success' : ($project->status === 'In Progress' ? 'primary' : 'secondary') }}">
                                        {{ $project->status }}
                                    </span>
                                    <small class="text-muted">{{ $project->projectManager->name }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Tidak ada proyek ditemukan</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endcan

            @can('view tasks')
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tasks me-2"></i>
                            Tugas ({{ $results['tasks']->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($results['tasks'] as $task)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">
                                    @php
                                        $taskUrl = '#';
                                        if (auth()->user()->hasRole('Project Manager')) {
                                            $taskUrl = route('pm.tasks.show', $task);
                                        } elseif (auth()->user()->hasRole(['Developer', 'QA', 'UI/UX Designer', 'Data Analyst'])) {
                                            $taskUrl = route('teammember.tasks.show', $task);
                                        }
                                    @endphp
                                    <a href="{{ $taskUrl }}" class="text-decoration-none">
                                        {{ $task->title }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-1">{{ Str::limit($task->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-{{ $task->status === 'Completed' ? 'success' : ($task->status === 'In Progress' ? 'primary' : 'secondary') }}">
                                        {{ $task->status }}
                                    </span>
                                    <small class="text-muted">{{ $task->project->name }}</small>
                                </div>
                                @if($task->assignedTo)
                                <small class="text-muted d-block">
                                    Ditugaskan ke: {{ $task->assignedTo->name }}
                                </small>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted mb-0">Tidak ada tugas ditemukan</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endcan

            @can('manage users')
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Pengguna ({{ $results['users']->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($results['users'] as $user)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none">
                                        {{ $user->name }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-1">{{ $user->email }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-info">{{ $user->getRoleNames()->first() ?? 'N/A' }}</span>
                                    <small class="text-muted">{{ $user->division?->name ?? '' }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Tidak ada pengguna ditemukan</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endcan
        </div>

        @if($results['projects']->isEmpty() && $results['tasks']->isEmpty() && (!$results['users'] || $results['users']->isEmpty()))
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada hasil ditemukan</h4>
                <p class="text-muted">Coba kata kunci yang berbeda atau lebih spesifik.</p>
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Mulai pencarian</h4>
            <p class="text-muted">Masukkan kata kunci untuk mencari proyek, tugas, atau pengguna.</p>
        </div>
    @endif
</div>
@endsection