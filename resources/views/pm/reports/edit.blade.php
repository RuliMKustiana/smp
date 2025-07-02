@extends('layouts.app')

@section('title', 'Edit Laporan Proyek')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Laporan</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Mengedit Laporan untuk Proyek: <strong>{{ $report->project->name }}</strong></h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pm.reports.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Method spoofing untuk request UPDATE --}}

                {{-- Input untuk Judul --}}
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

                {{-- Input untuk Konten --}}
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
                    <a href="{{ route('pm.reports.show', $report) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection