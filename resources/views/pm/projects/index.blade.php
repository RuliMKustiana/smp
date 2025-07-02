@extends('layouts.app')

@section('title', 'Manajemen Proyek')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                {{-- CARD HEADER --}}
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-white text-capitalize">Tabel Proyek</h6>
                            <a href="{{ route('pm.projects.create') }}" class="btn btn-light mb-0">Buat Proyek Baru</a>
                        </div>
                    </div>
                </div>

                {{-- CARD BODY --}}
                <div class="card-body px-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success text-white mx-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($projects->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Anda Belum Memiliki Proyek</h5>
                            <p class="text-muted small">Klik tombol "Buat Proyek Baru" untuk memulai.</p>
                        </div>
                    @else
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Proyek</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Prioritas</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penyelesaian</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                    <tr>
                                        {{-- Kolom Proyek --}}
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    {{-- Placeholder untuk ikon proyek --}}
                                                    <i class="fas fa-layer-group text-primary me-3 fs-5"></i>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $project->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        Deadline: {{ \Carbon\Carbon::parse($project->deadline_date)->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom Prioritas --}}
                                        <td>
                                            @php
                                                $priority_color = 'text-secondary';
                                                if ($project->priority == 'Tinggi') $priority_color = 'text-danger';
                                                if ($project->priority == 'Sedang') $priority_color = 'text-warning';
                                                if ($project->priority == 'Rendah') $priority_color = 'text-info';
                                            @endphp
                                            <p class="text-xs font-weight-bold mb-0 {{ $priority_color }}">{{ $project->priority }}</p>
                                        </td>

                                        {{-- Kolom Status --}}
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $project->status }}</span>
                                        </td>

                                        {{-- Kolom Penyelesaian (Progress Bar) --}}
                                        <td class="align-middle">
                                            @php
                                                $progress = 0;
                                                $progress_color = 'bg-secondary';
                                                if ($project->status === 'Selesai') { $progress = 100; $progress_color = 'bg-success'; }
                                                elseif ($project->status === 'In Progress') { $progress = 50; $progress_color = 'bg-info'; }
                                                elseif ($project->status === 'Dibatalkan') { $progress = 0; $progress_color = 'bg-danger'; }
                                            @endphp
                                            <div class="progress-wrapper w-75 mx-auto">
                                                <div class="progress-info">
                                                    <div class="progress-percentage">
                                                        <span class="text-xs font-weight-bold">{{ $progress }}%</span>
                                                    </div>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar {{ $progress_color }}" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress }}%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        {{-- Kolom Aksi --}}
                                        <td class="align-middle text-center">
                                            <a href="{{ route('pm.projects.show', $project) }}" class="text-info font-weight-bold text-xs" data-toggle="tooltip" title="Lihat Proyek">
                                                Lihat
                                            </a>
                                            <a href="{{ route('pm.projects.edit', $project) }}" class="text-secondary font-weight-bold text-xs mx-3" data-toggle="tooltip" title="Edit Proyek">
                                                Edit
                                            </a>
                                            <a href="javascript:;" class="text-danger font-weight-bold text-xs" data-bs-toggle="modal" data-bs-target="#deleteProjectModal-{{ $project->id }}" title="Hapus Proyek">
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
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HAPUS UNTUK SETIAP PROYEK --}}
@foreach ($projects as $project)
<div class="modal fade" id="deleteProjectModal-{{ $project->id }}" tabindex="-1" aria-labelledby="deleteProjectModalLabel-{{ $project->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProjectModalLabel-{{ $project->id }}">Konfirmasi Hapus Proyek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Anda yakin ingin menghapus proyek: <br><strong>"{{ $project->name }}"</strong>?</p>
                <p class="text-muted small">Menghapus proyek akan menghapus semua tugas di dalamnya.</p>
            </div>
            <div class="modal-footer d-flex justify-content-center border-top-0 gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('pm.projects.destroy', $project) }}" method="POST">
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
