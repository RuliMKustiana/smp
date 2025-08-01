@extends('layouts.app')

@section('title', 'Tambah Tugas Baru')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER HALAMAN --}}
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <div>
                        <h5 class="text-white text-capitalize mb-0">Tambah Tugas Baru</h5>
                        <p class="text-sm text-white opacity-8 mb-0">Untuk Proyek: {{ $project->name }}</p>
                    </div>
                    <a href="{{ route('pm.projects.show', $project) }}" class="btn btn-outline-light btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Proyek
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pm.tasks.store', $project) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    {{-- KOLOM KIRI: DETAIL UTAMA TUGAS --}}
                    <div class="col-lg-7">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Desain Halaman Login">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" placeholder="Jelaskan detail tugas di sini...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Persyaratan (Opsional)</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                      id="requirements" name="requirements" rows="3" 
                                      placeholder="Jelaskan kriteria penyelesaian tugas, misal: 'Desain harus responsif', 'Warna sesuai brand guide', dll.">{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attachments" class="form-label">Lampiran</label>
                            <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple>
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-xs">Anda dapat memilih beberapa file sekaligus.</div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: PENGATURAN & METADATA --}}
                    <div class="col-lg-5">
                        <div class="card bg-gray-100">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="assigned_to_id" class="form-label">Ditugaskan Kepada</label>
                                    <select class="form-select @error('assigned_to_id') is-invalid @enderror" 
                                            id="assigned_to_id" name="assigned_to_id">
                                        <option value="">Pilih Anggota Tim</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('assigned_to_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status Awal <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="To-Do" {{ old('status', 'To-Do') == 'To-Do' ? 'selected' : '' }}>To-Do</option>
                                        <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Blocked" {{ old('status') == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deadline" class="form-label">Tenggat Waktu</label>
                                            <input type="date" class="form-control @error('deadline') is-invalid @enderror" 
                                                   id="deadline" name="deadline" value="{{ old('deadline') }}"
                                                   min="{{ \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') }}"
                                                   max="{{ \Carbon\Carbon::parse($project->deadline_date)->format('Y-m-d') }}">
                                            @error('deadline')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="estimated_hours" class="form-label">Estimasi Jam</label>
                                            <input type="number" class="form-control @error('estimated_hours') is-invalid @enderror" 
                                                   id="estimated_hours" name="estimated_hours" value="{{ old('estimated_hours') }}" 
                                                   min="0" step="0.5" placeholder="Contoh: 8">
                                            @error('estimated_hours')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text text-xs mt-n2 mb-3">Tenggat waktu harus dalam rentang proyek.</div>

                                <div class="mb-3">
                                    <label class="form-label">Prioritas Proyek</label>
                                    <div class="form-control-plaintext">
                                        @if($project->priority === 'Tinggi')
                                            <span class="badge bg-gradient-danger">Tinggi</span>
                                        @elseif($project->priority === 'Sedang')
                                            <span class="badge bg-gradient-warning">Sedang</span>
                                        @elseif($project->priority === 'Rendah')
                                            <span class="badge bg-gradient-info">Rendah</span>
                                        @else
                                            <span class="badge bg-gradient-secondary">{{ $project->priority ?? 'N/A' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="horizontal dark mt-4">

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('pm.projects.show', $project) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="fas fa-save me-1"></i> Simpan Tugas
                    </button>
                </div>
            </form>
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
</script>
@endpush
