@extends('layouts.app')

@section('title', 'Project Manager Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- BARIS KARTU STATISTIK UTAMA -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Total Proyek</p>
                            <h4 class="stat-card-value">{{ $total_projects ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-dark">
                            <i class="fas fa-project-diagram fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Proyek Aktif</p>
                            <h4 class="stat-card-value">{{ $active_projects ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-blue">
                            <i class="fas fa-cogs fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Total Tugas</p>
                            <h4 class="stat-card-value">{{ $total_tasks ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-green">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <div class="stat-card-content">
                            <p class="stat-card-title">Tugas Terlewat</p>
                            <h4 class="stat-card-value">{{ $overdue_tasks ?? 0 }}</h4>
                        </div>
                        <div class="stat-card-icon-wrapper icon-gradient-red">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS GRAFIK DAN DAFTAR DEADLINE -->
        <div class="row">
            <div class="col-xl-7 col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-dark">Ringkasan Status Proyek</h6>
                    </div>
                    <div class="card-body">
                        @if (isset($project_status_labels) && count($project_status_labels) > 0)
                            @foreach ($project_status_labels as $index => $label)
                                @php
                                    $value = $project_status_values[$index] ?? 0;
                                    $totalProjectsForPercentage = $total_projects > 0 ? $total_projects : 1;
                                    $percentage = round(($value / $totalProjectsForPercentage) * 100);
                                    $status_class = \Illuminate\Support\Str::slug($label);
                                @endphp
                                <div class="status-bar-item mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="status-bar-label">{{ $label }}</span>
                                        <span class="status-bar-value">{{ $value }} Proyek</span>
                                    </div>
                                    <div class="progress mt-1" style="height: 8px;">
                                        <div class="progress-bar status-{{ $status_class }}" role="progressbar"
                                            style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">Tidak ada data status proyek.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Daftar Tugas Mendekati Deadline (Kode ini sudah aman) --}}
            <div class="col-xl-5 col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Tugas Mendekati Tenggat</h6>
                        <a href="{{ route('pm.tasks.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($tasks_near_deadline as $task)
                                <a href="{{ route('pm.tasks.show', $task['id']) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-dark">{{ \Illuminate\Support\Str::limit($task['title'], 35) }}
                                        </h6>
                                        <small class="text-muted">{{ $task['project_name'] }}</small>
                                    </div>
                                    <span
                                        class="badge rounded-pill {{ $task['badge_class'] }}">{{ $task['deadline_text'] }}</span>
                                </a>
                            @empty
                                <li class="list-group-item text-center text-muted py-5">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i><br>
                                    Tidak ada tugas mendekati tenggat.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL RINGKASAN PROYEK TERBARU -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Ringkasan Proyek Terbaru</h6>
                        <a href="{{ route('pm.projects.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Proyek</th>
                                        <th>Anggota Tim</th>
                                        <th>Status</th>
                                        <th>Penyelesaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projects_overview as $project)
                                        <tr>
                                            <td>
                                                <a href="{{ route('pm.projects.show', $project->id) }}"
                                                    class="text-dark text-decoration-none">
                                                    <strong>{{ $project->name }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="avatar-group">
                                                    @foreach ($project->members->take(4) as $member)
                                                        <a href="#" class="avatar avatar-sm rounded-circle"
                                                            data-bs-toggle="tooltip" title="{{ $member->name }}">
                                                            {{-- PERBAIKAN: Menghapus self-closing slash (/) agar sesuai standar HTML5 --}}
                                                            <img alt="Image placeholder"
                                                                src="{{ $member->profile_photo_url }}"
                                                                class="rounded-circle">
                                                        </a>
                                                    @endforeach
                                                    @if ($project->members->count() > 4)
                                                        <a href="#"
                                                            class="avatar avatar-sm rounded-circle text-white bg-secondary"
                                                            data-bs-toggle="tooltip"
                                                            title="+{{ $project->members->count() - 4 }} anggota lain">
                                                            <span>+{{ $project->members->count() - 4 }}</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td><span class="badge bg-info">{{ $project->status }}</span></td>
                                            <td>
                                                @php
                                                    $percentage =
                                                        $project->tasks_count > 0
                                                            ? round(
                                                                ($project->completed_tasks_count /
                                                                    $project->tasks_count) *
                                                                    100,
                                                            )
                                                            : 0;
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">{{ $percentage }}%</span>
                                                    <div class="progress flex-grow-1" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $percentage }}%;"
                                                            aria-valuenow="{{ $percentage }}"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">Anda belum mengelola proyek
                                                apapun.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- HAPUS SEMUA SCRIPT CHART.JS, SISAKAN HANYA UNTUK TOOLTIP --}}
    <script>
        // Inisialisasi Tooltip Bootstrap untuk avatar
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
