@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
    <div class="container-fluid profile-page">
        <!-- BAGIAN HEADER (TIDAK BERUBAH) -->
        <div class="row">
            <div class="col-12">
                <div class="profile-header">
                    <img src="{{ asset('images/background.jpg') }}" alt="Profile Header" class="profile-header-image">
                </div>
            </div>
        </div>

        <!-- KARTU PROFIL UTAMA DENGAN NAVIGASI TAB -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body blur shadow-blur mx-4 mt-n6">
                    <div class="card-body p-3">
                        <div class="row gx-4">
                            {{-- Foto Profil dan Nama (Tidak Berubah) --}}
                            <div class="col-auto">
                                <div class="avatar avatar-xl position-relative">
                                    <img src="{{ $user->avatar_url ?? 'https://placehold.co/100x100/ffffff/344767?text=' . strtoupper(substr($user->name, 0, 1)) }}"
                                        alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1">{{ $user->name }}</h5>
                                    <p class="mb-0 font-weight-bold text-sm">{{ $user->role->name ?? 'User' }}</p>
                                </div>
                            </div>
                            
                            {{-- NAVIGASI TAB DI SISI KANAN (INI YANG DIUBAH) --}}
                            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                                <div class="profile-tabs-wrapper position-relative end-0">
                                    {{-- 1. `role="tablist"` penting untuk aksesibilitas --}}
                                    <ul class="nav nav-pills nav-fill p-1 profile-tabs" id="profileTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            {{-- 2. Tambahkan atribut data-bs-toggle dan data-bs-target --}}
                                            <a class="nav-link mb-0 px-0 py-1 active" id="profile-info-tab" data-bs-toggle="pill" data-bs-target="#profile-info-content" type="button" role="tab" aria-controls="profile-info-content" aria-selected="true">
                                                <i class="fas fa-user-circle me-1"></i>
                                                <span class="ms-1">Profile</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link mb-0 px-0 py-1" id="password-tab" data-bs-toggle="pill" data-bs-target="#password-content" type="button" role="tab" aria-controls="password-content" aria-selected="false">
                                                <i class="fas fa-key me-1"></i>
                                                <span class="ms-1">Password</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA: FORM-FORM DI DALAM TAB -->
        <div class="container-fluid py-4">
            {{-- 3. Bungkus semua konten dengan div.tab-content --}}
            <div class="tab-content" id="profileTabContent">

                {{-- KONTEN TAB 1: PROFILE INFORMATION --}}
                {{-- 4. Bungkus setiap bagian dengan div.tab-pane dan berikan ID yang sesuai --}}
                <div class="tab-pane fade show active" id="profile-info-content" role="tabpanel" aria-labelledby="profile-info-tab">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5>Profile Information</h5>
                        </div>
                        <div class="card-body pt-0">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                {{-- KONTEN TAB 2: UPDATE PASSWORD --}}
                <div class="tab-pane fade" id="password-content" role="tabpanel" aria-labelledby="password-tab">
                     <div class="card shadow-sm">
                        <div class="card-header">
                            <h5>Update Password</h5>
                        </div>
                        <div class="card-body pt-0">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                {{-- Anda bisa menambahkan tab lain di sini jika perlu, misal untuk "Hapus Akun" --}}

            </div>
        </div>
    </div>
@endsection
