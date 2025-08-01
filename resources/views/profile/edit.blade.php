@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
    <div class="container-fluid profile-page">
        <div class="row">
            <div class="col-12">
                <div class="profile-header">
                    <img src="{{ asset('images/background.jpg') }}" alt="Profile Header" class="profile-header-image">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-body blur shadow-blur mx-4 mt-n6">
                    <div class="card-body p-3">
                        <div class="row gx-4">
                            <div class="col-auto">
                                <div class="avatar avatar-xl position-relative">
                                    <img src="{{ $user->profile_photo_url ?? 'https://placehold.co/100x100/ffffff/344767?text=' . strtoupper(substr($user->name, 0, 1)) }}"
                                        alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1">{{ $user->name }}</h5>
                                    {{-- =================== PERBAIKAN DI SINI =================== --}}
                                    <p class="mb-0 font-weight-bold text-sm">{{ $user->getRoleNames()->first() ?? 'User' }}</p>
                                    {{-- ======================================================= --}}
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                                <div class="profile-tabs-wrapper position-relative end-0">
                                    <ul class="nav nav-pills nav-fill p-1 profile-tabs" id="profileTab" role="tablist">
                                        <li class="nav-item" role="presentation">
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

        <div class="container-fluid py-4">
            <div class="tab-content" id="profileTabContent">

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

            </div>
        </div>
    </div>
@endsection