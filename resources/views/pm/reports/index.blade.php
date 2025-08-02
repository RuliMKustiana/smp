@extends('layouts.app')

@section('title', 'Laporan Proyek')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    {{-- CARD HEADER --}}
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center px-3">
                                <h6 class="text-white text-capitalize">Tabel Laporan Proyek</h6>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('pm.reports.create') }}" class="btn btn-light btn-sm mb-0">
                                        <i class="fas fa-plus me-1"></i> Buat Laporan
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle mb-0" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <h6 class="dropdown-header">Status</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('pm.reports.index') }}">Semua</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.reports.index', ['status' => 'Menunggu Persetujuan']) }}">Menunggu
                                                    Persetujuan</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.reports.index', ['status' => 'Disetujui']) }}">Disetujui</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.reports.index', ['status' => 'Ditolak']) }}">Ditolak</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header">Tipe</h6>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.reports.index', ['type' => 'progress']) }}">Progress</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.reports.index', ['type' => 'weekly']) }}">Mingguan</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.reports.index', ['type' => 'monthly']) }}">Bulanan</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('pm.reports.index', ['type' => 'final']) }}">Final</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD BODY --}}
                    <div class="card-body px-0 pb-2">
                        @if (session('success'))
                            <div class="alert alert-success text-white mx-3" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($reports->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Laporan</h5>
                                <p class="text-muted small">Mulai buat laporan untuk proyek yang Anda kelola.</p>
                                <a href="{{ route('pm.reports.create') }}" class="btn bg-gradient-dark mt-2">
                                    <i class="fas fa-plus me-1"></i> Buat Laporan Pertama
                                </a>
                            </div>
                        @else
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Laporan</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Tipe</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal Dibuat</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports as $report)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <i class="fas fa-file-alt text-primary me-3 fs-5"></i>
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">
                                                                {{ $report->title ?? Str::limit($report->content, 50) }}
                                                            </h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $report->project->name ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0 text-capitalize">
                                                        {{ $report->type }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    @if (in_array($report->status, ['Ditolak', 'rejected']))
                                                        <span class="badge badge-sm bg-gradient-danger">Ditolak - Perlu
                                                            Revisi</span>
                                                    @elseif(in_array($report->status, ['pending', 'Menunggu Persetujuan']))
                                                        <span class="badge badge-sm bg-gradient-warning">Menunggu
                                                            Validasi</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-success">Disetujui</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $report->created_at->format('d M Y') }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if (in_array($report->status, ['Ditolak', 'rejected']))
                                                        <a href="{{ route('pm.reports.edit', $report->id) }}"
                                                            class="btn btn-sm btn-info mb-0">Revisi</a>
                                                    @else
                                                        <a href="{{ route('pm.reports.show', $report->id) }}"
                                                            class="btn btn-sm btn-secondary mb-0">Detail</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-center mt-4">
                                {{ $reports->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS UNTUK SETIAP LAPORAN --}}
    @foreach ($reports as $report)
        <div class="modal fade" id="deleteReportModal-{{ $report->id }}" tabindex="-1"
            aria-labelledby="deleteReportModalLabel-{{ $report->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteReportModalLabel-{{ $report->id }}">Konfirmasi Hapus Laporan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p>Anda yakin ingin menghapus laporan:
                            <br><strong>"{{ $report->title ?? Str::limit($report->content, 50) }}"</strong>?
                        </p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center border-top-0 gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('pm.reports.destroy', $report) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
