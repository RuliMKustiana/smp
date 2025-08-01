@extends('layouts.app')

@section('title', 'Detail Tugas - ' . $task->title)

@section('content')
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center px-3">
                        <div>
                            <h5 class="text-white text-capitalize mb-0">{{ $task->title }}</h5>
                            <p class="text-sm text-white opacity-8 mb-0">
                                Proyek: {{ $task->project?->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            @can('update', $task)
                                <a href="{{ route('teammember.task-updates.create', $task) }}" class="btn btn-light btn-sm mb-0">
                                    <i class="fas fa-plus me-1"></i> Tambah Update
                                </a>
                            @endcan
                            <a href="{{ route('teammember.tasks.index') }}" class="btn btn-outline-light btn-sm mb-0">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="card">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs nav-justified" id="taskTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="detail-tab" data-bs-toggle="tab"
                                    data-bs-target="#detail-pane" type="button" role="tab">Detail</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="attachment-tab" data-bs-toggle="tab"
                                    data-bs-target="#attachment-pane" type="button" role="tab">Lampiran</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="discussion-tab" data-bs-toggle="tab"
                                    data-bs-target="#discussion-pane" type="button" role="tab">Diskusi</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="taskTabContent">
                            <div class="tab-pane fade show active" id="detail-pane" role="tabpanel">
                                @if ($task->description)
                                    <h6 class="text-sm text-muted">Deskripsi</h6>
                                    <p class="text-sm">{!! nl2br(e($task->description)) !!}</p>
                                @endif
                                @if ($task->requirements)
                                    <hr class="horizontal dark">
                                    <h6 class="text-sm text-muted">Persyaratan</h6>
                                    <p class="text-sm">{!! nl2br(e($task->requirements)) !!}</p>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="attachment-pane" role="tabpanel">
                                @if ($task->attachments && $task->attachments->count() > 0)
                                    <ul class="list-group list-group-flush">
                                        @foreach ($task->attachments as $attachment)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-paperclip text-secondary me-3"></i>
                                                    <span class="text-sm">{{ $attachment->file_name }}</span>
                                                </div>
                                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-dark mb-0">Download</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-center text-muted">Tidak ada lampiran untuk tugas ini.</p>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="discussion-pane" role="tabpanel">
                                <form action="{{ route('comments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="commentable_id" value="{{ $task->id }}">
                                    <input type="hidden" name="commentable_type" value="{{ get_class($task) }}">
                                    <div class="d-flex">
                                        <textarea name="body" class="form-control me-2" rows="1" placeholder="Tulis komentar..."></textarea>
                                        <button type="submit" class="btn btn-dark mb-0">Kirim</button>
                                    </div>
                                </form>
                                <hr class="horizontal dark">
                                @forelse($task->comments->sortByDesc('created_at') as $comment)
                                    <div class="d-flex mt-3">
                                        <img src="{{ $comment->user->profile_photo_url ?? 'https://placehold.co/40x40/EFEFEF/333333?text=' . substr($comment->user->name, 0, 1) }}"
                                            class="avatar avatar-sm rounded-circle me-3">
                                        <div class="flex-grow-1">
                                            <h6 class="text-sm mb-0">{{ $comment->user->name }}</h6>
                                            <p class="text-sm mb-1">{{ $comment->body }}</p>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-muted">Belum ada diskusi.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Riwayat Progress</h6>
                    </div>
                    <div class="card-body pt-3">
                        @forelse($task->updates->sortByDesc('created_at') as $update)
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    @if ($update->status_change)
                                        <i class="fas fa-info-circle text-info"></i>
                                    @else
                                        <i class="fas fa-comment-dots text-secondary"></i>
                                    @endif
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">
                                        {{ $update->description }}
                                        @if ($update->status_change)
                                            <span class="badge bg-gradient-info ms-2">{{ $update->status_change }}</span>
                                        @endif
                                    </h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                        {{ $update->created_at->isoFormat('D MMM Y, HH:mm') }} oleh
                                        {{ $update->user?->name }}
                                        @if ($update->hours_worked)
                                            <span class="ms-2">({{ $update->hours_worked }} jam kerja)</span>
                                        @endif
                                    </p>
                                    @if ($update->link)
                                        <div class="mt-2">
                                            <a href="{{ $update->link }}" target="_blank"
                                                class="text-sm text-primary fw-bold">
                                                <i class="fas fa-link fa-sm me-1"></i> Lihat Link
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted p-3">Belum ada update progress untuk tugas ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Informasi Tugas</h6>
                    </div>
                    <div class="card-body pt-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-sm">Status</span>
                                @php
                                    $status_class = 'bg-gradient-secondary';
                                    if ($task->status === 'In Progress') {
                                        $status_class = 'bg-gradient-info';
                                    }
                                    if ($task->status === 'Completed') {
                                        $status_class = 'bg-gradient-success';
                                    }
                                    if ($task->status === 'Revisi') {
                                        $status_class = 'bg-gradient-warning';
                                    }
                                    if ($task->status === 'Blocked') {
                                        $status_class = 'bg-gradient-danger';
                                    }
                                @endphp
                                <span class="badge {{ $status_class }}">{{ $task->status }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-sm">Prioritas Proyek</span>
                                @php
                                    $priority = $task->project?->priority;
                                    $priority_color = 'bg-gradient-secondary';
                                    if ($priority == 'Tinggi') {
                                        $priority_color = 'bg-gradient-danger';
                                    }
                                    if ($priority == 'Sedang') {
                                        $priority_color = 'bg-gradient-warning';
                                    }
                                    if ($priority == 'Rendah') {
                                        $priority_color = 'bg-gradient-info';
                                    }
                                @endphp
                                <span class="badge {{ $priority_color }}">{{ $priority ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-sm">Dibuat Oleh (PM)</span>
                                <strong class="text-sm">{{ $task->assignedBy?->name ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-sm">Tenggat Waktu</span>
                                <strong class="text-sm @if ($task->isOverdue()) text-danger @endif">
                                    {{ $task->deadline ? $task->deadline->isoFormat('D MMMM Y') : '-' }}
                                </strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-block {
            position: relative;
            padding-left: 30px;
        }

        .timeline-step {
            position: absolute;
            left: 0;
            top: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #e9ecef;
        }

        .timeline-step i {
            font-size: 12px;
            color: #6c757d;
        }

        .timeline-block:not(:last-child)::before {
            content: "";
            position: absolute;
            left: 11px;
            top: 24px;
            width: 2px;
            height: calc(100% - 24px);
            background-color: #e9ecef;
        }
    </style>
@endpush
