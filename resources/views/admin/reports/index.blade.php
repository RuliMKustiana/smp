@extends('layouts.app')

@section('title', 'Validasi Laporan')

@section('content')
<div class="container-fluid">
    {{-- Bagian Header dan Filter Anda sudah bagus, kita pertahankan --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Validasi Laporan</h1>
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-filter me-1"></i> 
                Filter: {{ ucfirst(request('status', 'Semua')) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}">Semua</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'pending']) }}">Pending</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'validated']) }}">Disetujui</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'rejected']) }}">Ditolak</a></li>
            </ul>
        </div>
    </div>

    {{-- Kartu untuk menampung tabel --}}
    <div class="card shadow-sm">
        <div class="card-body">
            @if($reports->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Laporan</h5>
                    <p class="text-muted">Tidak ada laporan dengan status '{{ request('status', 'apapun') }}' saat ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Judul Laporan</th>
                                <th class="text-center">Proyek</th>
                                <th class="text-center">Pelapor</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td class="text-center"><strong>{{ $report->id }}</strong></td>
                                    <td class="text-center">{{ $report->title ?? Str::limit($report->content, 50) }}</td>
                                    <td class="text-center">{{ $report->project->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $report->submittedBy->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $report->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        @if($report->status === 'Disetujui' || $report->status === 'validated')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($report->status === 'Ditolak' || $report->status === 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- INI SATU-SATUNYA TOMBOL AKSI YANG DIPERLUKAN --}}
                                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn bg-gradient-dark">
                                            <i class="fas fa-search-plus me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{-- Menambahkan parameter filter saat paginasi --}}
                    {{ $reports->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection