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
                            <a href="{{ route('admin.users.create') }}" class="btn bg-gradient-green mb-0 text-white">Tambah Pengguna</a>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Author</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Function</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Daftar</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    {{-- Kolom Author (Nama & Email) --}}
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                {{-- Placeholder untuk gambar profil --}}
                                                <img src="https://placehold.co/40x40/EFEFEF/333333?text={{ substr($user->name, 0, 1) }}" class="avatar avatar-sm me-3 border-radius-lg" alt="user image">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Function (Peran) --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->role->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $user->username }}</p>
                                    </td>

                                    {{-- Kolom Status --}}
                                    <td class="align-middle text-center text-sm">
                                        @if ($user->is_active)
                                            <span class="badge badge-sm bg-gradient-success">Online</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">Offline</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Tanggal Daftar (Employed) --}}
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/y') }}</span>
                                    </td>

                                    {{-- Kolom Aksi (View, Edit, Delete) --}}
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            {{-- Tombol Lihat Detail --}}
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-icon-only btn-rounded btn-outline-info mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-icon-only btn-rounded btn-outline-warning mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Pengguna">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Tombol Delete (Memicu Modal) --}}
                                            <button type="button" class="btn btn-icon-only btn-rounded btn-outline-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{ $user->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Pengguna">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Konfirmasi Hapus (ditempatkan di sini agar unik untuk setiap user) --}}
                                <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteUserModalLabel-{{ $user->id }}">Konfirmasi Penghapusan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                                <p>Anda yakin ingin menghapus pengguna: <br><strong>{{ $user->name }}</strong>?</p>
                                                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn bg-gradient-danger">Ya, Hapus</button>
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
                    
                    {{-- Pagination Links --}}
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
    // Inisialisasi Tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
