@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Edit Tugas: {{ $task->title }}</h1>
                    <div class="d-flex gap-2">
                        {{-- Tombol kembali sekarang mengarah ke detail tugas yang benar --}}
                        <a href="{{ route('pm.tasks.show', $task->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        {{-- Route action disesuaikan untuk mengirim kedua parameter --}}
                        <form action="{{ route('pm.tasks.update', ['project' => $project->id, 'task' => $task->id]) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Judul Tugas <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $task->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="10">{{ old('description', $task->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        {{-- PERBAIKAN: 'name' diubah menjadi 'assigned_to_id' dan logika 'old' diperbaiki --}}
                                        <label for="assigned_to_id" class="form-label">Ditugaskan Kepada <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('assigned_to_id') is-invalid @enderror"
                                            id="assigned_to_id" name="assigned_to_id" required>
                                            <option value="">Pilih Anggota Tim</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('assigned_to_id', $task->assigned_to_id) == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Prioritas Proyek</label>
                                        <div class="form-control-plaintext">
                                            @if ($project->priority === 'Tinggi')
                                                <span class="badge bg-danger">Tinggi</span>
                                            @elseif($project->priority === 'Sedang')
                                                <span class="badge bg-warning text-dark">Sedang</span>
                                            @elseif($project->priority === 'Rendah')
                                                <span class="badge bg-info">Rendah</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $project->priority ?? 'N/A' }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            {{-- PERBAIKAN: Nilai disesuaikan dengan aturan validasi controller --}}
                                            <option value="Belum Dikerjakan"
                                                {{ old('status', $task->status) === 'Belum Dikerjakan' ? 'selected' : '' }}>
                                                Belum Dikerjakan</option>
                                            <option value="In Progress"
                                                {{ old('status', $task->status) === 'In Progress' ? 'selected' : '' }}>In
                                                Progress</option>
                                            <option value="Selesai"
                                                {{ old('status', $task->status) === 'Selesai' ? 'selected' : '' }}>Selesai
                                            </option>
                                            <option value="Revisi"
                                                {{ old('status', $task->status) === 'Revisi' ? 'selected' : '' }}>Revisi
                                            </option>
                                            <option value="Blocked"
                                                {{ old('status', $task->status) === 'Blocked' ? 'selected' : '' }}>Blocked
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        {{-- PERBAIKAN: 'name' diubah menjadi 'deadline' dan format tanggal diperbaiki --}}
                                        <label for="deadline" class="form-label">Tenggat Waktu <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                                            id="deadline" name="deadline"
                                            value="{{ old('deadline', \Carbon\Carbon::parse($task->deadline)->format('Y-m-d')) }}"
                                            min="{{ \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') }}"
                                            max="{{ \Carbon\Carbon::parse($project->deadline_date)->format('Y-m-d') }}"
                                            required>
                                        @error('deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Tugas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('attachments').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const maxSize = 10 * 1024 * 1024; // 10MB

            let hasError = false;
            files.forEach(file => {
                if (file.size > maxSize) {
                    alert(`File ${file.name} terlalu besar. Maksimal 10MB.`);
                    hasError = true;
                }
            });

            if (hasError) {
                e.target.value = '';
            }
        });

        // Set minimum date to today
        document.getElementById('start_date').min = new Date().toISOString().split('T')[0];
        document.getElementById('due_date').min = new Date().toISOString().split('T')[0];
    </script>
@endpush
