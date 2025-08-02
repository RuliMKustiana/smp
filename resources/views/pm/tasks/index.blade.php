@extends('layouts.app')

@section('title', 'Semua Tugas Proyek')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    {{-- CARD HEADER --}}
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center px-3">
                                <h6 class="text-white text-capitalize">Tabel Semua Tugas</h6>
                                {{-- Filter Buttons --}}
                                <div class="d-flex gap-2">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle mb-0" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            Status: {{ request('status', 'Semua') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.tasks.index', ['project' => request('project')]) }}">Semua
                                                    Status</a></li>
                                            @foreach ($statuses as $status)
                                                <li><a class="dropdown-item"
                                                        href="{{ route('pm.tasks.index', ['status' => $status, 'project' => request('project')]) }}">{{ $status }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle mb-0" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-project-diagram me-1"></i>
                                            Proyek: {{ $projects->firstWhere('id', request('project'))?->name ?? 'Semua' }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            style="max-height: 280px; overflow-y: auto;">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.tasks.index', ['status' => request('status')]) }}">Semua
                                                    Proyek</a></li>
                                            @foreach ($projects as $project)
                                                <li><a class="dropdown-item"
                                                        href="{{ route('pm.tasks.index', ['project' => $project->id, 'status' => request('status')]) }}">{{ $project->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD BODY --}}
                    <div class="card-body px-0 pb-2">
                        @if ($tasks->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak Ada Tugas Ditemukan</h5>
                                <p class="text-muted small">Coba ubah filter Anda atau buat tugas baru.</p>
                            </div>
                        @else
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tugas</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Ditugaskan</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Penyelesaian</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            <tr>
                                                {{-- Kolom Tugas & Proyek --}}
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">
                                                                <a href="{{ route('pm.tasks.show', $task->id) }}"
                                                                    class="text-dark fw-bold text-decoration-none">
                                                                    {{ $task->title }}
                                                                </a>
                                                            </h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $task->project?->name ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </td>

                                                {{-- Kolom Ditugaskan --}}
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $task->assignedTo?->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-secondary mb-0">
                                                        @if ($task->deadline)
                                                            Tenggat:
                                                            {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                                            @if (\Carbon\Carbon::parse($task->deadline)->isPast() && $task->status !== 'Selesai')
                                                                <span class="text-danger">(Terlewat)</span>
                                                            @endif
                                                        @endif
                                                    </p>
                                                </td>

                                                {{-- Kolom Status --}}
                                                <td class="align-middle text-center text-sm">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $task->status }}</span>
                                                </td>

                                                {{-- Kolom Penyelesaian (Progress Bar) --}}
                                                <td class="align-middle">
                                                    <div class="progress-wrapper w-75 mx-auto">
                                                        <div class="progress-info">
                                                            <div class="progress-percentage">
                                                                {{-- Memanggil accessor percentage --}}
                                                                <span
                                                                    class="text-xs font-weight-bold">{{ $task->progress_percentage }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="progress">
                                                            {{-- Memanggil accessor percentage & color --}}
                                                            <div class="progress-bar {{ $task->progress_color_class }}"
                                                                role="progressbar"
                                                                aria-valuenow="{{ $task->progress_percentage }}"
                                                                aria-valuemin="0" aria-valuemax="100"
                                                                style="width: {{ $task->progress_percentage }}%;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                {{-- Kolom Aksi (DIPERBAIKI) --}}
                                                <td class="align-middle text-center">
                                                    <a href="{{ route('pm.tasks.show', $task->id) }}"
                                                        class="text-info font-weight-bold text-xs" data-toggle="tooltip"
                                                        data-original-title="Lihat detail tugas">
                                                        Lihat
                                                    </a>
                                                    <a href="{{ route('pm.tasks.edit', [$task->project_id, $task->id]) }}"
                                                        class="text-secondary font-weight-bold text-xs ms-3"
                                                        data-toggle="tooltip" data-original-title="Edit tugas">
                                                        Edit
                                                    </a>
                                                    <a href="javascript:;" class="text-danger font-weight-bold text-xs ms-3"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteTaskModal-{{ $task->id }}">
                                                        Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-center mt-4">
                                {{ $tasks->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS (Struktur tidak berubah) --}}
    @foreach ($tasks as $task)
        <div class="modal fade" id="deleteTaskModal-{{ $task->id }}" tabindex="-1"
            aria-labelledby="deleteTaskModalLabel-{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTaskModalLabel-{{ $task->id }}">Konfirmasi Hapus Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p>Anda yakin ingin menghapus tugas: <br><strong>"{{ $task->title }}"</strong>?</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center border-top-0 gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('pm.tasks.destroy', [$task->project_id, $task->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
