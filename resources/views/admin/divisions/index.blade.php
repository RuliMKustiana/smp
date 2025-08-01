@extends('layouts.app')

@section('title', 'Manajemen Divisi')

@section('content')
<div class="container-fluid py-4">
    <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h6 class="text-white text-capitalize">Manajemen Divisi</h6>
                    <a href="{{ route('admin.divisions.create') }}" class="btn btn-light mb-0">Tambah Divisi</a>
                </div>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            @if(session('success'))
                <div class="alert alert-success text-white mx-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-white mx-3">{{ session('error') }}</div>
            @endif
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Divisi</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Anggota</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($divisions as $division)
                        <tr>
                            <td><div class="px-3 py-1"><h6 class="mb-0 text-sm">{{ $division->name }}</h6></div></td>
                            <td class="align-middle text-center"><span class="badge bg-gradient-info">{{ $division->users_count }}</span></td>
                            <td class="align-middle text-center">
                                <a href="{{ route('admin.divisions.edit', $division) }}" class="btn btn-link text-secondary font-weight-bold text-xs">Edit</a>
                                <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus divisi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger font-weight-bold text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4">Tidak ada data divisi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">{{ $divisions->links() }}</div>
        </div>
    </div>
</div>
@endsection
