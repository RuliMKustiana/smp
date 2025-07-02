@extends('layouts.app')

@section('title', 'Papan Kanban - ' . $project->name)

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER KANBAN --}}
    <div class="card">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    {{-- Judul Proyek dan Info --}}
                    <div>
                        <h5 class="text-white text-capitalize mb-0">{{ $project->name }}</h5>
                        <p class="text-sm text-white opacity-8 mb-0">
                            Status: {{ $project->status }}
                            <span class="mx-2">|</span>
                            Deadline: {{ \Carbon\Carbon::parse($project->deadline_date)->isoFormat('D MMMM Y') }}
                        </p>
                    </div>
                    {{-- Tombol Aksi --}}
                    <div class="btn-group">
                        <a href="{{ route('pm.projects.show', $project) }}" class="btn btn-outline-light btn-sm mb-0">
                            <i class="fas fa-list me-1"></i> Tampilan Daftar
                        </a>
                        <a href="{{ route('pm.tasks.create', $project->id) }}" class="btn btn-light btn-sm mb-0">
                            <i class="fas fa-plus me-1"></i> Tambah Tugas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-2">
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success text-white alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Komponen Livewire untuk Papan Kanban --}}
            <div class="mt-4">
                @livewire('project.kanban-board', ['projectId' => $project->id])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Script untuk Livewire Sortable --}}
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
@endpush
