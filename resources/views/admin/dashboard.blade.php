@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard Administrator')

@section('content')
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-content">
                        <p class="stat-card-title">Total Pengguna</p>
                        <h4 class="stat-card-value">{{ $total_users ?? 0 }}</h4>
                        {{-- Anda bisa menambahkan persentase perubahan di sini jika ada datanya --}}
                        {{-- <p class="stat-card-change text-success">+5%</p> --}}
                    </div>
                    <div class="stat-card-icon-wrapper icon-gradient-dark">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-content">
                        <p class="stat-card-title">Total Proyek</p>
                        <h4 class="stat-card-value">{{ $total_projects ?? 0 }}</h4>
                    </div>
                    <div class="stat-card-icon-wrapper icon-gradient-blue">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-content">
                        <p class="stat-card-title">Proyek Aktif</p>
                        <h4 class="stat-card-value">{{ $active_projects ?? 0 }}</h4>
                    </div>
                    <div class="stat-card-icon-wrapper icon-gradient-green">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-card-content">
                        <p class="stat-card-title">Laporan Menunggu</p>
                        <h4 class="stat-card-value">{{ $pending_reports_count ?? 0 }}</h4>
                    </div>
                    <div class="stat-card-icon-wrapper icon-gradient-blue-green">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Laporan Menunggu Validasi (Bagian ini tidak diubah) --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="h5 mb-0 text-dark">
                <i class="fas fa-hourglass-half me-2 text-warning"></i>
                Laporan Menunggu Validasi
            </h3>
            {{-- Penambahan: Badge untuk menampilkan jumlah laporan yang sedang pending --}}
            @if (isset($pending_reports) && $pending_reports->isNotEmpty())
                <span class="badge bg-warning text-dark rounded-pill">{{ $pending_reports->count() }}</span>
            @endif
        </div>
        <div class="card-body">
            @if (isset($pending_reports) && $pending_reports->isEmpty())
                {{-- Perbaikan: Tampilan "Empty State" yang lebih menarik --}}
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="text-muted">Luar Biasa!</h4>
                    <p class="text-muted">Tidak ada laporan yang menunggu validasi saat ini.</p>
                </div>
            @else
                {{-- Perbaikan: Tabel dengan styling yang lebih baik --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul Laporan</th>
                                <th scope="col">Pelapor</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Contoh Loop Data dengan Blade --}}
                            @foreach ($pending_reports as $report)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $report->title }}</td>
                                    <td>{{ $report->submittedBy->name ?? 'N/A' }}</td>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.reports.show', $report->id) }}"
                                            class="btn bg-gradient-dark">
                                            <i class="fas fa-search-plus me-1"></i> Detail & Validasi
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
@endsection
