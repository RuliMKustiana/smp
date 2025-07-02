@extends('layouts.app')

@section('title', 'Tugas Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Tugas Saya</h1>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index') }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'pending']) }}">Pending</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'in_progress']) }}">Sedang Dikerjakan</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'completed']) }}">Selesai</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'on_hold']) }}">Ditunda</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sort"></i> Urutkan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['sort' => 'due_date']) }}">Tenggat Waktu</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['sort' => 'priority']) }}">Prioritas</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['sort' => 'created_at']) }}">Terbaru</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Task Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card icon-gradient-yellow text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card icon-gradient-blue text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['in_progress'] }}</h4>
                                    <p class="mb-0">Sedang Dikerjakan</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-play fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card icon-gradient-green text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['completed'] }}</h4>
                                    <p class="mb-0">Selesai</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card icon-gradient-dark text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['on_hold'] }}</h4>
                                    <p class="mb-0">Ditunda</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-pause fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Tugas dengan Tampilan Baru --}}
    <div class="row mt-4">
        @forelse($tasks as $task)
            <div class="col-xl-4 col-md-6 mb-4">
                @php
                    $priorityClass = 'border-light';
                    if ($task->priority === 'high') $priorityClass = 'border-danger';
                    if ($task->priority === 'medium') $priorityClass = 'border-warning';
                    if ($task->priority === 'low') $priorityClass = 'border-info';
                @endphp
                <div class="card h-100 task-card border-start border-4 {{ $priorityClass }}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <p class="text-sm text-muted mb-1">{{ $task->project->name }}</p>
                            @if($task->status === 'pending') <span class="badge bg-gradient-warning">Pending</span>
                            @elseif($task->status === 'in_progress') <span class="badge bg-gradient-info">Dikerjakan</span>
                            @elseif($task->status === 'completed') <span class="badge bg-gradient-success">Selesai</span>
                            @elseif($task->status === 'on_hold') <span class="badge bg-gradient-secondary">Ditunda</span>
                            @endif
                        </div>
                        
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('employee.tasks.show', $task) }}" class="text-dark text-decoration-none stretched-link">
                                {{ $task->title }}
                            </a>
                        </h5>
                        
                        @if($task->description)
                            <p class="card-text text-muted small mb-3">{{ Str::limit($task->description, 100) }}</p>
                        @endif

                        <div class="mt-auto">
                            <hr class="horizontal dark my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt text-secondary me-2"></i>
                                    <span class="text-secondary text-sm">
                                        {{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}
                                        @if($task->due_date && $task->due_date->isPast() && $task->status !== 'completed')
                                            <span class="text-danger fw-bold">(Terlambat)</span>
                                        @endif
                                    </span>
                                </div>

                                @if($task->status !== 'completed')
                                    <div class="dropdown">
                                        <a href="javascript:;" class="btn btn-link text-secondary ps-0 pe-2 mb-0" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v text-secondary"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($task->status === 'pending')
                                                <li><form action="{{ route('employee.tasks.update-status', $task) }}" method="POST"> @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="in_progress">
                                                    <button type="submit" class="dropdown-item"><i class="fas fa-play me-2"></i>Mulai Kerjakan</button>
                                                </form></li>
                                            @elseif($task->status === 'in_progress' || $task->status === 'on_hold')
                                                <li><form action="{{ route('employee.tasks.update-status', $task) }}" method="POST"> @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Yakin tugas sudah selesai?')"><i class="fas fa-check me-2"></i>Tandai Selesai</button>
                                                </form></li>
                                            @endif
                                            @if($task->status === 'in_progress')
                                                <li><form action="{{ route('employee.tasks.update-status', $task) }}" method="POST"> @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="on_hold">
                                                    <button type="submit" class="dropdown-item"><i class="fas fa-pause me-2"></i>Tunda</button>
                                                </form></li>
                                            @elseif($task->status === 'on_hold')
                                                 <li><form action="{{ route('employee.tasks.update-status', $task) }}" method="POST"> @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="in_progress">
                                                    <button type="submit" class="dropdown-item"><i class="fas fa-play me-2"></i>Lanjutkan</button>
                                                </form></li>
                                            @endif
                                        </ul>
                                    </div>
                                @else
                                    <i class="fas fa-check-circle text-success" data-bs-toggle="tooltip" title="Selesai"></i>
                                @endif
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
    
    @if($tasks->count() > 0)
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
    box-shadow: 0 10px 20px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.06);
}
.dropdown {
    position: relative;
    z-index: 2;
}
</style>
@endpush
