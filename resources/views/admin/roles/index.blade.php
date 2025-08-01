@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
    <div class="container-fluid py-4">
        {{-- Header Halaman --}}
        <div class="card mb-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center px-3">
                        <h5 class="text-white text-capitalize mb-0">Manajemen Role</h5>
                        @can('create roles')
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-light btn-sm mb-0">
                                <i class="fas fa-plus me-1"></i> Tambah Role Baru
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
                <span class="alert-text"><strong>Sukses!</strong> {{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
                <span class="alert-icon align-middle">
                    <span class="material-icons text-md">warning</span>
                </span>
                <span class="alert-text"><strong>Error!</strong> {{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Tabel Data --}}
        <div class="card">
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Role
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jumlah
                                    Pengguna</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $role->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-info">{{ $role->users_count }} Pengguna</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if ($role->name !== 'Admin')
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('edit roles')
                                                    @if ($role->users_count > 0)
                                                        <button
                                                            class="btn btn-icon-only btn-rounded btn-outline-warning mb-0 disabled"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Role tidak bisa diedit karena memiliki pengguna">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    @else
                                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                                            class="btn btn-icon-only btn-rounded btn-outline-warning mb-0"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Role">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                @endcan
                                                @can('delete roles')
                                                    @if ($role->users_count > 0)
                                                        <button
                                                            class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 disabled"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Role tidak bisa dihapus karena memiliki pengguna">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-icon-only btn-rounded btn-outline-danger mb-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteRoleModal-{{ $role->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Role">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <i class="fas fa-users-cog fa-3x text-secondary"></i>
                                        <p class="text-muted mt-3 mb-0">Belum ada data role.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    @foreach ($roles as $role)
        @if ($role->name !== 'Admin')
            <div class="modal fade" id="deleteRoleModal-{{ $role->id }}" tabindex="-1"
                aria-labelledby="deleteRoleModalLabel-{{ $role->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteRoleModalLabel-{{ $role->id }}">Konfirmasi Penghapusan
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <p>Anda yakin ingin menghapus role: <br><strong>{{ $role->name }}</strong>?</p>
                            <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn bg-gradient-danger">Ya, Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
