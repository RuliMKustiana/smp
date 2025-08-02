@extends('layouts.app')

@section('title', 'Detail & Validasi Laporan')

@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <div>
                        <h5 class="text-white text-capitalize mb-0">{{ $report->title ?? 'Laporan Proyek' }}</h5>
                        <p class="text-sm text-white opacity-8 mb-0">Proyek: {{ $report->project?->name ?? 'N/A' }}</p>
                    </div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        {{-- KOLOM KIRI: DETAIL LAPORAN --}}
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 bg-white">
                    <h5 class="m-0 font-weight-bold">Informasi Laporan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex text-muted small mb-3">
                        <div class="me-3">
                            <i class="fas fa-user me-1"></i> Pelapor: <strong>{{ $report->submittedBy->name ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt me-1"></i> Dibuat: <strong>{{ $report->created_at->format('d M Y, H:i') }}</strong>
                        </div>
                    </div>
                    <hr>
                    <h5>Konten Laporan</h5>
                    <div class="p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $report->content }}</div>

                    @if($report->attachments->isNotEmpty())
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
                    {{-- ✅ PERUBAHAN UTAMA: Periksa apakah validator_id masih kosong (NULL) --}}
                    @if(is_null($report->validator_id))
                        {{-- Jika validator_id KOSONG, berarti laporan belum divalidasi. Tampilkan form. --}}
                        <form action="{{ route('admin.reports.process', $report->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="validation_notes" class="form-label"><strong>Catatan Revisi / Persetujuan</strong></label>
                                <textarea name="validation_notes" id="validation_notes" class="form-control" rows="4" 
                                          placeholder="Isi catatan ini jika laporan ditolak (wajib) atau jika ada pesan tambahan saat menyetujui (opsional)."></textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" name="action" value="rejected" class="btn btn-danger">
                                    <i class="fas fa-times-circle me-1"></i> Tolak & Kembalikan
                                </button>
                                <button type="submit" name="action" value="validated" class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i> Setujui Laporan
                                </button>
                            </div>
                        </form>
                    @else
                        {{-- Jika validator_id SUDAH ADA, berarti laporan sudah diproses. Tampilkan hasilnya. --}}
                        @php
                            $isApproved = in_array(trim($report->status), ['validated', 'Disetujui']);
                            $alertClass = $isApproved ? 'success' : 'danger';
                            $iconClass = $isApproved ? 'check-circle' : 'times-circle';
                        @endphp
                        <div class="alert alert-{{ $alertClass }} text-white">
                            <h5 class="alert-heading text-white">
                                <i class="fas fa-{{ $iconClass }}"></i>
                                Laporan ini telah {{ $isApproved ? 'Disetujui' : 'Ditolak' }}.
                            </h5>
                            <hr>
                            <p class="mb-1"><strong>Oleh:</strong> {{ $report->validator->name ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $report->validated_at ? $report->validated_at->format('d M Y, H:i') : 'N/A' }}</p>
                            @if($report->validation_notes)
                                <p class="mb-0 mt-2"><strong>Catatan:</strong><br>{{ $report->validation_notes }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection