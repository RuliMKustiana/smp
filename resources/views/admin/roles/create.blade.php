@extends('layouts.app')

@section('title', 'Tambah Role Baru')

@section('content')
<div class="container-fluid py-4">

    {{-- Header Halaman --}}
    <div class="card mb-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-green shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h5 class="text-white text-capitalize mb-0">Tambah Role Baru</h5>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-light btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Tambah Role --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                {{-- Input Nama Role --}}
                <div class="mb-4">
                    <label for="name" class="form-label">Nama Role</label>
                    <input type="text" class="form-control p-2 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Daftar Permissions --}}
                <div class="mb-3">
                    <label class="form-label">Permissions</label>
                    <div class="row">
                        @forelse($permissions as $permission)
                            <div class="col-md-3 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}">
                                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">Tidak ada permission ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                    @error('permissions')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Submit --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn bg-gradient-dark">
                        <i class="fas fa-save me-2"></i> Simpan Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection