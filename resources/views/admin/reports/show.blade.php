@extends('layouts.app')

@section('title', 'Detail & Validasi Laporan')

@section('content')
<div class="container-fluid">
    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Laporan</h1>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row">
        {{-- KOLOM KIRI: DETAIL LAPORAN --}}
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h5 class="m-0 font-weight-bold">Informasi Laporan {{ $report->id }}</h5>
                </div>
                <div class="card-body">
                    {{-- Judul dan Info Pelapor --}}
                    <h3>{{ $report->title ?? 'Laporan Proyek' }}</h3>
                    <div class="d-flex text-muted small mb-3">
                        <div class="me-3">
                            <i class="fas fa-user me-1"></i> Pelapor: <strong>{{ $report->submittedBy->name ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt me-1"></i> Dibuat: <strong>{{ $report->created_at->format('d M Y, H:i') }}</strong>
                        </div>
                    </div>
                    <hr>

                    {{-- Konten Laporan --}}
                    <h5>Konten Laporan</h5>
                    <div class="p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $report->content }}</div>

                    {{-- Lampiran jika ada --}}
                    @if($report->attachments && $report->attachments->count() > 0)
                        <hr>
                        <h5 class="mt-4">Lampiran</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($report->attachments as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>
                                        <i class="fas fa-paperclip me-2 text-muted"></i>
                                        {{ $attachment->file_name ?? 'lampiran' }}
                                    </span>
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: PANEL AKSI DAN STATUS --}}
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header py-3 bg-white">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gavel me-2"></i>Status & Tindakan
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Tampilkan Info Validasi jika laporan SUDAH diproses --}}
                    @if($report->status !== 'Menunggu Persetujuan')
                        <div class="alert alert-{{ $report->status === 'Disetujui' ? 'success' : 'danger' }}">
                            <h5 class="alert-heading">
                                <i class="fas fa-{{ $report->status === 'Disetujui' ? 'check-circle' : 'times-circle' }}"></i>
                                Laporan ini telah {{ $report->status }}.
                            </h5>
                            <hr>
                            <p class="mb-1"><strong>Oleh:</strong> {{ $report->validator->name ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $report->validated_at ? \Carbon\Carbon::parse($report->validated_at)->format('d M Y, H:i') : 'N/A' }}</p>
                            @if($report->validation_notes)
                                <p class="mb-0 mt-2"><strong>Catatan:</strong><br>{{ $report->validation_notes }}</p>
                            @endif
                        </div>
                    @else
                        {{-- Tampilkan Form Aksi jika laporan MASIH MENUNGGU --}}
                        
                        <!-- Form untuk Validasi (Setujui) -->
                        <form action="{{ route('admin.reports.validate', $report->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="validation_notes" class="form-label"><strong>Catatan Persetujuan (Opsional)</strong></label>
                                <textarea class="form-control" id="validation_notes" name="validation_notes" rows="4" placeholder="Contoh: Laporan lengkap dan bukti valid."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2">
                                <i class="fas fa-check-circle me-2"></i> Setujui Laporan Ini
                            </button>
                        </form>

                        <hr class="my-4">

                        <!-- Form untuk Penolakan -->
                        <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label"><strong class="text-danger">Alasan Penolakan (Wajib Diisi)</strong></label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" placeholder="Contoh: Bukti yang dilampirkan tidak cukup untuk verifikasi." required minlength="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 py-2">
                                <i class="fas fa-times-circle me-2"></i> Tolak Laporan Ini
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection