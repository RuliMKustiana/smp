@extends('layouts.app')

@section('title', 'Edit Laporan Proyek')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Mengedit Laporan untuk Proyek: <strong>{{ $report->project->name }}</strong></h5>
        </div>
        <div class="card-body">
            
            @if(in_array($report->status, ['Ditolak', 'rejected']) && $report->validation_notes)
                <div class="alert alert-danger text-white mb-4" role="alert">
                    <h5 class="alert-heading text-white"><i class="fas fa-exclamation-circle me-2"></i>Laporan Ini Perlu Revisi</h5>
                    <hr>
                    <p class="mb-1"><strong>Catatan dari Admin:</strong></p>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $report->validation_notes }}</p>
                </div>
            @endif

            <form action="{{ route('pm.reports.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $report->title) }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Konten Laporan <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" 
                              name="content" 
                              rows="12" 
                              required>{{ old('content', $report->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('pm.reports.index') }}" class="btn btn-secondary">Batal</a>
                    
                    <button type="submit" class="btn btn-primary">
                        @if(in_array($report->status, ['Ditolak', 'rejected']))
                            <i class="fas fa-paper-plane me-2"></i>Kirim Ulang Laporan
                        @else
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection