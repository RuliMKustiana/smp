<header class="top-header">
    <div class="breadcrumbs">
        <span class="text-muted"></span><strong>@yield('title', 'Dashboard')</strong>
    </div>
    <div class="header-right">
        @auth
            <div class="user-profile dropdown header-item">
                <a href="#" class="dropdown-toggle d-flex align-items-center" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="{{ auth()->user()->avatar_url ?? 'https://placehold.co/32x32/007bff/ffffff?text=' . strtoupper(substr(auth()->user()->name, 0, 1)) }}"
                        alt="Avatar" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                    <span class="d-none d-sm-inline-block">{{ auth()->user()->name }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" style="min-width: 240px;">
                    <li>
                        <div class="d-flex align-items-center px-3 pt-2 pb-3 dropdown-profile-header">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ auth()->user()->avatar_url ?? 'https://placehold.co/60x60/ffffff/2a9d8f?text=' . strtoupper(substr(auth()->user()->name, 0, 1)) }}"
                                    alt="Avatar" class="rounded-circle" width="60" height="60"
                                    style="object-fit: cover; border: 2px solid #fff;">
                            </div>
                            <div class="flex-grow-1">
                                <strong class="d-block">{{ auth()->user()->name }}</strong>
                                <small class="text-muted">{{ auth()->user()->role->name ?? 'User' }}</small>
                            </div>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider mt-0">
                    </li>

                    <li class="dropdown-item py-2 position-relative">
                        <i class="fas fa-user-edit fa-fw me-2"></i> Profil Saya
                        <a href="{{ route('profile.edit') }}" class="stretched-link"></a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</header>
