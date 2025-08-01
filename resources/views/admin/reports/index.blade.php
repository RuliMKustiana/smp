@extends('layouts.app')

@section('title', 'Validasi Laporan')

@section('content')
<div class="container-fluid py-4">
    {{-- Header dan Filter --}}
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h6 class="text-white text-capitalize">Validasi Laporan Proyek</h6>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle mb-0" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> 
                            Filter: {{ Str::ucfirst(request('status', 'Semua')) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'pending']) }}">Pending</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'validated']) }}">Disetujui</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'rejected']) }}">Ditolak</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-white">{{ session('success') }}</div>
    @endif

    @php
        // Memisahkan laporan menjadi dua koleksi: pending dan yang sudah divalidasi
        $pendingReports = $reports->filter(function ($report) {
            return in_array($report->status, ['pending', 'Menunggu Persetujui']);
        });
        $validatedReports = $reports->filter(function ($report) {
            return !in_array($report->status, ['pending', 'Menunggu Persetujui']);
        });
    @endphp

    {{-- TABEL 1: LAPORAN MENUNGGU VALIDASI --}}
    <div class="card my-4">
        <div class="card-header">
            <h5 class="mb-0">Menunggu Validasi <span class="badge bg-gradient-warning ms-2">{{ $pendingReports->count() }}</span></h5>
            <p class="text-sm mb-0">Laporan yang memerlukan tindakan Anda.</p>
        </div>
        <div class="card-body px-0 pb-2">
            @if($pendingReports->isEmpty())
                <p class="text-center text-muted p-4">Tidak ada laporan yang menunggu validasi.</p>
            @else
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Laporan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelapor</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingReports as $report)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $report->title ?? Str::limit($report->content, 40) }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $report->project->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><p class="text-xs font-weight-bold mb-0">{{ $report->submittedBy->name ?? 'N/A' }}</p></td>
                                    <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $report->created_at->format('d M Y') }}</span></td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-outline-dark mb-0">
                                            <i class="fas fa-search-plus me-1"></i> Validasi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- TABEL 2: RIWAYAT VALIDASI --}}
    <div class="card my-4">
        <div class="card-header">
            <h5 class="mb-0">Riwayat Validasi</h5>
            <p class="text-sm mb-0">Daftar laporan yang telah Anda setujui atau tolak.</p>
        </div>
        <div class="card-body px-0 pb-2">
            @if($validatedReports->isEmpty())
                <p class="text-center text-muted p-4">Belum ada riwayat validasi.</p>
            @else
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Laporan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelapor</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($validatedReports as $report)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $report->title ?? Str::limit($report->content, 40) }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $report->project->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><p class="text-xs font-weight-bold mb-0">{{ $report->submittedBy->name ?? 'N/A' }}</p></td>
                                    <td class="align-middle text-center text-sm">
                                        @if($report->status === 'Disetujui' || $report->status === 'validated')
                                            <span class="badge badge-sm bg-gradient-success">Disetujui</span>
                                        @elseif($report->status === 'Ditolak' || $report->status === 'rejected')
                                            <span class="badge badge-sm bg-gradient-danger">Ditolak</span>
                                        @endif
                                        <p class="text-xs text-secondary mb-0 mt-1">
                                            oleh {{ $report->validator?->name ?? 'N/A' }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-outline-dark mb-0">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    
    {{-- Pagination Links --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $reports->appends(request()->query())->links() }}
    </div>
</div>
@endsection
