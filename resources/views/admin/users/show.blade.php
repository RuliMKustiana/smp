@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Detail Pengguna</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn bg-gradient-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn bg-gradient-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-body blur shadow-blur mt-n2">
                            <div class="card-body text-center">
                                @if ($user->profile_photo_path)
                                    <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Profile Photo"
                                        class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                        style="width: 150px; height: 150px;">
                                        <i class="fas fa-user fa-4x text-white"></i>
                                    </div>
                                @endif

                                <h4 class="card-title">{{ $user->name }}</h4>
                                <p class="text-muted">{{ $user->username }}</p>

                                <span
                                    class="badge {{ $user->is_active ? 'bg-gradient-success' : 'bg-gradient-danger' }} mb-2">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>

                                <div class="mt-3">
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $user->is_active ? 'bg-gradient-warning' : 'bg-gradient-success' }}"
                                            onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} pengguna ini?')">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card card-body blur shadow-blur mt-n2">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Pengguna</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nama Lengkap:</strong>
                                        <p>{{ $user->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Username:</strong>
                                        <p>{{ $user->username }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Email:</strong>
                                        <p>{{ $user->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nomor Telepon:</strong>
                                        <p>{{ $user->phone_number ?: '-' }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Role:</strong>
                                        <p>
                                            <span class="badge bg-gradient-dark">{{ $user->role->name }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Divisi:</strong>
                                        <p>{{ $user->division ?: '-' }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Tanggal Bergabung:</strong>
                                        <p>{{ $user->created_at->format('d F Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Terakhir Diupdate:</strong>
                                        <p>{{ $user->updated_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>

                                @if ($user->email_verified_at)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Email Terverifikasi:</strong>
                                            <p>{{ $user->email_verified_at->format('d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($user->isProjectManager() || $user->isEmployee())
                            <div class="card shadow-sm mt-4">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i>Aktivitas Pengguna
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        {{-- ======================================= --}}
                                        {{-- == STATISTIK UNTUK PROJECT MANAGER     == --}}
                                        {{-- ======================================= --}}
                                        @if ($user->isProjectManager())
                                            {{-- KARTU 1: PROYEK DIKELOLA --}}
                                            <div class="col-lg-4 col-md-6">
                                                <div class="stat-card-v3">
                                                    <div class="stat-card-v3-header">
                                                        <div class="stat-card-v3-icon icon-bg-purple">
                                                            <i class="fas fa-briefcase"></i>
                                                        </div>
                                                        <span class="stat-card-v3-title">Proyek Dikelola</span>
                                                    </div>
                                                    <h4 class="stat-card-v3-value">{{ $user->managedProjects->count() }}
                                                    </h4>
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-purple" role="progressbar"
                                                            style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- KARTU 2: TUGAS DIBUAT --}}
                                            <div class="col-lg-4 col-md-6">
                                                <div class="stat-card-v3">
                                                    <div class="stat-card-v3-header">
                                                        <div class="stat-card-v3-icon icon-bg-blue">
                                                            <i class="fas fa-tasks"></i>
                                                        </div>
                                                        <span class="stat-card-v3-title">Tugas Dibuat</span>
                                                    </div>
                                                    <h4 class="stat-card-v3-value">{{ $user->createdTasks->count() }}</h4>
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-blue" role="progressbar"
                                                            style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- KARTU 3: LAPORAN DIBUAT --}}
                                            <div class="col-lg-4 col-md-6">
                                                <div class="stat-card-v3">
                                                    <div class="stat-card-v3-header">
                                                        <div class="stat-card-v3-icon icon-bg-teal">
                                                            <i class="fas fa-file-alt"></i>
                                                        </div>
                                                        <span class="stat-card-v3-title">Laporan Dibuat</span>
                                                    </div>
                                                    <h4 class="stat-card-v3-value">{{ $user->reports->count() }}</h4>
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-teal" role="progressbar"
                                                            style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- ======================================= --}}
                                        {{-- == STATISTIK UNTUK KARYAWAN (EMPLOYEE) == --}}
                                        {{-- ======================================= --}}
                                        @if ($user->isEmployee())
                                            {{-- KARTU 1: PROYEK TERLIBAT --}}
                                            <div class="col-lg-4 col-md-6">
                                                <div class="stat-card-v3">
                                                    <div class="stat-card-v3-header">
                                                        <div class="stat-card-v3-icon icon-bg-orange">
                                                            <i class="fas fa-project-diagram"></i>
                                                        </div>
                                                        <span class="stat-card-v3-title">Proyek Terlibat</span>
                                                    </div>
                                                    <h4 class="stat-card-v3-value">{{ $user->projects->count() }}</h4>
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-orange" role="progressbar"
                                                            style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- KARTU 2: TOTAL TUGAS --}}
                                            <div class="col-lg-4 col-md-6">
                                                <div class="stat-card-v3">
                                                    @php
                                                        // Hitung total tugas di awal agar bisa dipakai lagi
                                                        $totalTasks = $user->assignedTasks->count();
                                                    @endphp
                                                    <div class="stat-card-v3-header">
                                                        <div class="stat-card-v3-icon icon-bg-cyan">
                                                            <i class="fas fa-clipboard-list"></i>
                                                        </div>
                                                        <span class="stat-card-v3-title">Total Tugas</span>
                                                    </div>
                                                    <h4 class="stat-card-v3-value">{{ $totalTasks }}</h4>
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-cyan" role="progressbar"
                                                            style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- KARTU 3: TUGAS SELESAI --}}
                                            <div class="col-lg-4 col-md-6">
                                                <div class="stat-card-v3">
                                                    <div class="stat-card-v3-header">
                                                        <div class="stat-card-v3-icon icon-bg-green">
                                                            <i class="fas fa-clipboard-check"></i>
                                                        </div>
                                                        <span class="stat-card-v3-title">Tugas Selesai</span>
                                                    </div>
                                                    @php
                                                        $completedTasks = $user->assignedTasks
                                                            ->where('status', 'Selesai')
                                                            ->count();
                                                        $percentage =
                                                            $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                                    @endphp
                                                    <h4 class="stat-card-v3-value">{{ $completedTasks }}</h4>
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-green" role="progressbar"
                                                            style="width: {{ $percentage }}%;"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
