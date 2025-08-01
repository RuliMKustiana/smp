@extends('layouts.app')

@section('title', isset($project) ? 'Edit Proyek' : 'Buat Proyek Baru')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h6 class="text-white text-capitalize">{{ isset($project) ? 'Edit Proyek: ' . $project->name : 'Buat Proyek Baru' }}</h6>
                    <a href="{{ route('pm.projects.index') }}" class="btn btn-outline-light btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ isset($project) ? route('pm.projects.update', $project) : route('pm.projects.store') }}" method="POST">
                @csrf
                @if(isset($project))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Proyek</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $project->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="12">{{ old('description', $project->description ?? '') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card bg-gray-100">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', isset($project) ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}" required>
                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label for="deadline_date" class="form-label">Tanggal Deadline</label>
                                    <input type="date" class="form-control @error('deadline_date') is-invalid @enderror" id="deadline_date" name="deadline_date" value="{{ old('deadline_date', isset($project) ? \Carbon\Carbon::parse($project->deadline_date)->format('Y-m-d') : '') }}" required>
                                    @error('deadline_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        @foreach(['Belum Dimulai', 'In Progress', 'Selesai', 'Revisi', 'Dibatalkan'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $project->status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Prioritas</label>
                                    <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                        @foreach(['Rendah', 'Sedang', 'Tinggi'] as $priority)
                                            <option value="{{ $priority }}" {{ old('priority', $project->priority ?? '') == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                                        @endforeach
                                    </select>
                                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="team_members" class="form-label">Anggota Tim</label>
                            <select class="form-select" name="team_members[]" id="team_members" multiple placeholder="Pilih anggota tim...">
                                @foreach($teamMembers as $member)
                                    <option value="{{ $member->id }}" {{ in_array($member->id, old('team_members', $team_member_ids ?? [])) ? 'selected' : '' }}>
                                        {{ $member->name }} ({{ $member->division?->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text">Anda dapat mencari dan memilih lebih dari satu anggota.</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('pm.projects.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn bg-gradient-dark">Simpan Proyek</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#team_members', {
                plugins: ['remove_button'], 
                create: false, 
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
@endpush
