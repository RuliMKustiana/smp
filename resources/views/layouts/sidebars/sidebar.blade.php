<!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
    <div class="sidebar-header">
        {{-- Membuat logo bisa diklik untuk kembali ke dashboard --}}
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan" class="sidebar-logo">
        </a>
    </div>

    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @auth
            <!-- Menu Admin -->
            @if (auth()->user()->isAdmin())
                <li class="nav-title">Admin</li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index')}}" class="{{ request()->routeIs('admin.reports.index') ? 'active' : ''}}">
                        <i class="fas fa-file-alt"></i>
                        <span>Validasi Laporan</span>
                    </a>
                </li>
            @endif

            <!-- Menu Project Manager -->
            @if (auth()->user()->isProjectManager())
                <li class="nav-title">Proyek</li>
                <li class="nav-item">
                    <a href="{{ route('pm.projects.index') }}" class="{{ request()->routeIs('pm.projects.index') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Daftar Proyek</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pm.tasks.index')}}" class="{{ request()->routeIs('pm.tasks.index') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Manajemen Tugas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pm.reports.index')}}"class="{{ request()->routeIs('pm.reports.index') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Report</span>
                    </a>
                </li>
            @endif

            <!-- Menu Karyawan -->
            @if (auth()->user()->isEmployee())
                <li class="nav-title">Karyawan</li>
                <li class="nav-item">
                    <a href="{{ route('employee.tasks.index')}}"class="{{ request()->routeIs('employee.tasks.index') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tugas Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.projects.index')}}"class="{{ request()->routeIs('employee.projects.index') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Project</span>
                    </a>
                </li>
            @endif
        @endauth
    </ul>
</aside>
