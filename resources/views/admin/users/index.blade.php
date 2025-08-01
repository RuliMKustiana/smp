@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center px-3">
                                <h6 class="text-white text-capitalize">Tabel Pengguna</h6>
                                @can('create users')
                                    <a href="{{ route('admin.users.create') }}"
                                        class="btn bg-gradient-green mb-0 text-white">Tambah Pengguna</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">

                        @if (session('success'))
                            <div class="alert alert-success text-white mx-3">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Author</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Function</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tanggal Daftar</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="{{ $user->profile_photo_url }}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user image">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $user->getRoleNames()->first() ?? 'Tanpa Peran' }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $user->division?->name ?? 'N/A' }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if ($user->is_active)
                                                    <span class="badge badge-sm bg-gradient-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-secondary">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/y') }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                                        class="btn btn-icon-only btn-rounded btn-outline-info mb-0"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @can('edit users')
                                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                                            class="btn btn-icon-only btn-rounded btn-outline-warning mb-0"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Edit Pengguna">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @can('delete users')
                                                        @if (auth()->id() !== $user->id)
                                                            <button type="button"
                                                                class="btn btn-icon-only btn-rounded btn-outline-danger mb-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteUserModal-{{ $user->id }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Hapus Pengguna">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1"
                                            aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="deleteUserModalLabel-{{ $user->id }}">Konfirmasi
                                                            Penghapusan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                                        <p>Anda yakin ingin menghapus pengguna:
                                                            <br><strong>{{ $user->name }}</strong>?
                                                        </p>
                                                        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn bg-gradient-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn bg-gradient-danger">Ya,
                                                                Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">Tidak ada data pengguna.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
