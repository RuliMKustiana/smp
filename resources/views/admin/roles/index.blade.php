@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Role</h1>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Role
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Role</th>
                            <th>Slug</th>
                            <th>Jumlah Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                </td>
                                <td>
                                    <code>{{ $role->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $role->users_count }} pengguna</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($role->users_count == 0)
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary" disabled title="Tidak dapat dihapus karena masih digunakan">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-users-cog fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Tidak ada role ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($roles->count() > 0)
        <div class="mt-4">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Catatan:</strong> Role yang sedang digunakan oleh pengguna tidak dapat dihapus. 
                Anda perlu mengubah role pengguna terlebih dahulu sebelum menghapus role tersebut.
            </div>
        </div>
    @endif
</div>
@endsection
