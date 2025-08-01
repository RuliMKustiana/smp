@extends('layouts.app')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    {{-- KESALAHAN LOGIKA DIPERBAIKI --}}
                    <h1 class="h3 mb-0">
                        @can('view reviewable tasks')
                            Tugas untuk Direview
                        @elsecan('view assigned tasks')
                            Tugas Saya
                        @endcan
                    </h1>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-filter"></i>
                                {{ request('status') ? Str::title(str_replace('-', ' ', request('status'))) : 'Filter Status' }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('teammember.tasks.index', request()->except('status', 'page')) }}">Semua
                                        Status</a></li>
                                @foreach ($statusesForFilter as $status)
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('teammember.tasks.index', array_merge(request()->except('page'), ['status' => Str::slug($status)])) }}">
                                            {{ $status }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sort"></i> Urutkan
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('teammember.tasks.index', array_merge(request()->except('page'), ['sort' => 'due_date'])) }}">Tenggat
                                        Waktu</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('teammember.tasks.index', array_merge(request()->except('page'), ['sort' => 'priority'])) }}">Prioritas</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    {{-- KESALAHAN PENUTUP DIPERBAIKI --}}
                    @can ('view assigned tasks')
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-yellow text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['pending'] ?? 0 }}</h4>
                                            <p class="mb-0">Pending</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-clock fa-2x"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-blue text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['in_progress'] ?? 0 }}</h4>
                                            <p class="mb-0">Dikerjakan</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-play fa-2x"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-red text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['in_review'] ?? 0 }}</h4>
                                            <p class="mb-0">Direview</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-search fa-2x"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-green text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['completed'] ?? 0 }}</h4>
                                            <p class="mb-0">Selesai</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-check fa-2x"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elsecan ('view reviewable tasks')
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-blue text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['in_review'] ?? 0 }}</h4>
                                            <p class="mb-0">Siap Direview</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-search fa-2x"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-red text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['revisi'] ?? 0 }}</h4>
                                            <p class="mb-0">Revisi</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-exclamation-circle fa-2x"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-green text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['completed'] ?? 0 }}</h4>
                                            <p class="mb-0">Selesai</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-check fa-2x"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card icon-gradient-dark text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['on_hold'] ?? 0 }}</h4>
                                            <p class="mb-0">Ditunda</p>
                                        </div>
                                        <div class="align-self-center"><i class="fas fa-ban fa-2x"></i></div>
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
                                    <div class="d-flex align-items-center mb-3">
                                        <div>
                                            <h6 class="card-title fw-bold mb-0">
                                                <a href="{{ route('teammember.tasks.show', $task) }}"
                                                    class="text-dark text-decoration-none stretched-link">
                                                    {{ Str::limit($task->title, 50) }}
                                                </a>
                                            </h6>
                                            <p class="text-sm text-muted mb-0">{{ $task->project->name }}</p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center text-sm text-muted mb-3">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span>
                                            @if ($task->deadline)
                                                @if ($task->isOverdue())
                                                    <span class="text-danger fw-bold">Terlambat
                                                        {{ $task->deadline->diffForHumans() }}</span>
                                                @else
                                                    Tenggat {{ $task->deadline->diffForHumans() }}
                                                @endif
                                            @else
                                                <span>Tanpa tenggat</span>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @php
                                                    $status_class = 'bg-secondary';
                                                    if ($task->status === 'In Progress') {
                                                        $status_class = 'bg-info';
                                                    }
                                                    if ($task->status === 'Completed') {
                                                        $status_class = 'bg-success';
                                                    }
                                                    if ($task->status === 'Revisi') {
                                                        $status_class = 'bg-warning';
                                                    }
                                                    if ($task->status === 'Blocked') {
                                                        $status_class = 'bg-danger';
                                                    }
                                                    if ($task->status === 'In Review') {
                                                        $status_class = 'bg-primary';
                                                    }
                                                @endphp
                                                <span class="badge {{ $status_class }}">{{ $task->status }}</span>
                                            </div>
                                            <div class="avatar-group">
                                                @foreach ($task->project->members->take(3) as $member)
                                                    <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ $member->name }}">
                                                        <img src="{{ $member->profile_photo_url ?? 'https://placehold.co/40x40/EFEFEF/333333?text=' . substr($member->name, 0, 1) }}"
                                                            alt="{{ $member->name }}">
                                                    </a>
                                                @endforeach
                                                @if ($task->project->members->count() > 3)
                                                    <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="+{{ $task->project->members->count() - 3 }} lainnya">
                                                        <div
                                                            class="bg-light text-dark d-flex align-items-center justify-content-center h-100">
                                                            <small>+{{ $task->project->members->count() - 3 }}</small>
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>
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
                                    <p class="text-muted">Tidak ada tugas yang cocok dengan filter Anda.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($tasks->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .task-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
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