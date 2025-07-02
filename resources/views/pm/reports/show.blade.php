@extends('layouts.app')

@section('title', 'Detail Laporan Proyek')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER HALAMAN --}}
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    {{-- Judul Laporan dan Info Proyek --}}
                    <div>
                        <h5 class="text-white text-capitalize mb-0">Detail Laporan</h5>
                        <p class="text-sm text-white opacity-8 mb-0">
                            Proyek: {{ $report->project->name }}
                        </p>
                    </div>
                    {{-- Tombol Aksi --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('pm.reports.index') }}" class="btn btn-outline-light btn-sm mb-0">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        @if($report->status === 'Menunggu Persetujuan')
                            <a href="{{ route('pm.reports.edit', $report) }}" class="btn btn-light btn-sm mb-0">
                                <i class="fas fa-edit me-1"></i> Edit Laporan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- KOLOM KIRI: KONTEN LAPORAN --}}
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">{{ $report->title }}</h6>
                    {{-- PERBAIKAN: Menggunakan Carbon::parse untuk memastikan ini adalah objek tanggal --}}
                    <p class="text-sm mb-0">Dibuat pada: {{ \Carbon\Carbon::parse($report->created_at)->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                </div>
                <hr class="horizontal dark my-3">
                <div class="card-body pt-0">
                    {{-- Gunakan kelas untuk menjaga format teks (enter, spasi, dll) --}}
                    <p class="text-sm" style="white-space: pre-wrap;">{{ $report->content }}</p>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: STATUS VALIDASI --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Status Validasi</h6>
                </div>
                <div class="card-body">
                    {{-- Status Box --}}
                    @php
                        $status_color = 'warning';
                        $status_icon = 'fas fa-hourglass-half';
                        $status_text = 'Menunggu Persetujuan';
                        if ($report->status === 'Disetujui') {
                            $status_color = 'success';
                            $status_icon = 'fas fa-check-circle';
                            $status_text = 'Disetujui';
                        } elseif ($report->status === 'Ditolak') {
                            $status_color = 'danger';
                            $status_icon = 'fas fa-times-circle';
                            $status_text = 'Ditolak';
                        }
                    @endphp
                    <div class="alert alert-{{ $status_color }} text-white mb-0" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="{{ $status_icon }} fs-4 me-3"></i>
                            <div>
                                <h6 class="alert-heading text-white mb-0">{{ $status_text }}</h6>
                                @if($report->validated_at)
                                {{-- PERBAIKAN: Menggunakan Carbon::parse untuk memastikan ini adalah objek tanggal --}}
                                <span class="text-xs">Pada: {{ \Carbon\Carbon::parse($report->validated_at)->isoFormat('D MMM Y, HH:mm') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Catatan dari Admin --}}
                    @if($report->validation_notes)
                        <div class="mt-4">
                            <h6 class="text-sm">Catatan dari Admin:</h6>
                            <div class="p-3 bg-gray-100 border-radius-lg">
                                <p class="text-sm text-dark mb-0">{{ $report->validation_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
