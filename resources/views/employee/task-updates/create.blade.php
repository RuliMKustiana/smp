@extends('layouts.app')

@section('title', 'Update Tugas: ' . $task->title)

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER HALAMAN --}}
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <div>
                        <h5 class="text-white text-capitalize mb-0">Update Tugas: {{ $task->title }}</h5>
                        <p class="text-sm text-white opacity-8 mb-0">Proyek: {{ $task->project->name }}</p>
                    </div>
                    <a href="{{ route('employee.tasks.show', $task) }}" class="btn btn-outline-light btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Tugas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('employee.task-updates.store', $task) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- KOLOM KIRI: FORM UTAMA --}}
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Deskripsi Progress</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Update <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                      rows="8" required placeholder="Jelaskan progress atau update yang telah Anda kerjakan...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attachments" class="form-label">Lampiran</label>
                            <input type="file" class="form-control @error('attachments') is-invalid @enderror" id="attachments" name="attachments[]" multiple>
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-xs">Anda bisa memilih lebih dari satu file.</div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Link Terkait (Opsional)</label>
                            <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}" placeholder="Contoh: https://github.com/user/repo/pull/1">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PENGATURAN UPDATE --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Pengaturan Update</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Ubah Status Tugas</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="">Tidak mengubah status</option>
                                @if ($task->status !== 'in_progress')
                                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                @endif
                                @if ($task->status !== 'completed')
                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                @endif
                                @if ($task->status !== 'on_hold')
                                    <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>Ditunda</option>
                                @endif
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hours_worked" class="form-label">Jam Kerja</label>
                            <input type="number" class="form-control @error('hours_worked') is-invalid @enderror"
                                   id="hours_worked" name="hours_worked" value="{{ old('hours_worked') }}"
                                   min="0" step="0.5" placeholder="Contoh: 2.5">
                            @error('hours_worked')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-xs">Berapa jam yang Anda kerjakan untuk update ini?</div>
                        </div>
                    </div>
                    <div class="card-footer pt-0">
                        <button type="submit" class="btn bg-gradient-dark w-100">
                            <i class="fas fa-save me-1"></i> Simpan Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

    // Auto-fill hours worked based on time
    const startTime = new Date();
    document.getElementById('hours_worked').addEventListener('focus', function() {
        if (!this.value) {
            const now = new Date();
            const diffHours = (now - startTime) / (1000 * 60 * 60);
            if (diffHours > 0.1) { // More than 6 minutes
                this.value = Math.round(diffHours * 2) / 2; // Round to nearest 0.5
            }
        }
    });
</script>
@endpush
