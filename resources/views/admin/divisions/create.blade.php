@extends('layouts.app')

@section('title', 'Tambah Divisi Baru')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h6 class="text-white text-capitalize">Tambah Divisi Baru</h6>
                    <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline-light btn-sm mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.divisions.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Divisi</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Front-End Developer Senior">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn bg-gradient-dark">Simpan Divisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
