@extends('layouts.app')

@section('title', 'Detail Proyek - ' . $project->name)

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER HALAMAN --}}
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <div>
                        <h5 class="text-white text-capitalize mb-0">{{ $project->name }}</h5>
                        <p class="text-sm text-white opacity-8 mb-0">
                            Manajer Proyek: {{ $project->projectManager?->name ?? 'N/A' }}
                        </p>
                    </div>
                    <a href="{{ route('teammember.projects.index') }}" class="btn btn-outline-light btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Proyek
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- KOLOM KIRI --}}
        <div class="col-lg-8 mb-4 mb-lg-0">
            {{-- KARTU OVERVIEW (Deskripsi & Progress) --}}
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Overview Proyek</h6>
                </div>
                <div class="card-body">
                    @if($project->description)
                        <p class="text-sm">{!! nl2br(e($project->description)) !!}</p>
                        <hr class="horizontal dark">
                    @endif

                    @php
                        $totalTasks = $project->tasks->count();
                        $completedTasks = $project->tasks->where('status', 'Selesai')->count();
                        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 text-sm">Progress Penyelesaian</h6>
                        <span class="text-sm">{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai</span>
                    </div>
                    <div class="progress-wrapper">
                        <div class="progress">
                            <div class="progress-bar bg-gradient-success" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ $progress }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU DAFTAR TUGAS SAYA --}}
            <div class="card mt-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Tugas Saya</h6>
                        <span class="badge bg-gradient-dark">{{ $myTasks->count() }} Tugas</span>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <tbody>
                                @forelse($myTasks as $task)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">
                                                    <a href="{{ route('teammember.tasks.show', $task->id) }}" class="text-dark fw-bold text-decoration-none">{{ $task->title }}</a>
                                                </h6>
                                                
                                                {{-- PENAMBAHAN: Menampilkan lampiran tugas --}}
                                                {{-- CATATAN: Pastikan controller memuat relasi 'attachments' -> with('attachments') atau $project->load('myTasks.attachments') --}}
                                                @if($task->attachments && $task->attachments->count() > 0)
                                                    <div class="mt-2">
                                                        @foreach($task->attachments as $attachment)
                                                            {{-- Kode ini menggunakan 'file_path' dan 'file_name' sesuai model Attachment Anda --}}
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-xs me-3 text-decoration-none">
                                                                <i class="fas fa-paperclip text-secondary me-1"></i>{{ $attachment->file_name ?? 'Lampiran' }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @php
                                            $status_class = 'bg-gradient-secondary';
                                            if ($task->status === 'In Progress') $status_class = 'bg-gradient-info';
                                            if ($task->status === 'Selesai') $status_class = 'bg-gradient-success';
                                            if ($task->status === 'Revisi') $status_class = 'bg-gradient-warning';
                                            if ($task->status === 'Blocked') $status_class = 'bg-gradient-danger';
                                        @endphp
                                        <span class="badge badge-sm {{ $status_class }}">{{ $task->status }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ \Carbon\Carbon::parse($task->deadline)->isoFormat('D MMM Y') }}
                                            @if($task->isOverdue())
                                                <span class="text-danger ms-1">(Terlewat)</span>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Anda tidak memiliki tugas pada proyek ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-lg-4">
            {{-- KARTU DETAIL PROYEK --}}
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Detail Proyek</h6>
                </div>
                <div class="card-body pt-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-sm">Status</span>
                            <span class="badge bg-gradient-blue">{{ $project->status }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-sm">Prioritas</span>
                            @php
                                $priority_color = 'bg-gradient-secondary';
                                if ($project->priority == 'Tinggi') $priority_color = 'bg-gradient-danger';
                                if ($project->priority == 'Sedang') $priority_color = 'bg-gradient-warning';
                                if ($project->priority == 'Rendah') $priority_color = 'bg-gradient-info';
                            @endphp
                            <span class="badge {{ $priority_color }}">{{ $project->priority }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- KARTU TIM PROYEK --}}
            <div class="card mt-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Tim Proyek</h6>
                </div>
                <div class="card-body pt-3">
                    <ul class="list-group list-group-flush">
                        @forelse($project->members as $member)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <img src="{{ $member->profile_photo_url ?? 'https://placehold.co/40x40/EFEFEF/333333?text='.substr($member->name, 0, 1) }}" alt="{{ $member->name }}" class="avatar avatar-sm rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0 text-sm">{{ $member->name }}</h6>
                                    <p class="text-xs text-secondary mb-0">{{ $member->role?->name ?? 'Member' }}</p>
                                </div>
                            </div>
                            @if ($member->id === auth()->id())
                                <span class="badge bg-gradient-blue">Anda</span>
                            @endif
                        </li>
                        @empty
                        <li class="list-group-item px-0">
                            <p class="text-sm text-muted">Belum ada anggota tim.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
