<aside class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan" class="sidebar-logo">
        </a>
    </div>

    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if (auth()->user()->can('view users') || auth()->user()->can('view roles') || auth()->user()->can('view divisions') || auth()->user()->can('validate reports'))
            <li class="nav-title">Admin</li>
        @endif

        @if (auth()->user()->can('view users') || auth()->user()->can('view roles') || auth()->user()->can('view divisions'))
            @php
                $isUserManagementActive = request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.divisions.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $isUserManagementActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#userManagement" role="button" aria-expanded="{{ $isUserManagementActive ? 'true' : 'false' }}">
                    <i class="fas fa-users"></i>
                    <span>Manajemen Pengguna</span>
                </a>
                <div class="collapse {{ $isUserManagementActive ? 'show' : '' }}" id="userManagement">
                    <ul class="nav flex-column ms-3">
                        @can('view users')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <span>Daftar Pengguna</span>
                                </a>
                            </li>
                        @endcan
                        @can('view roles')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                    <span>Manajemen Peran</span>
                                </a>
                            </li>
                        @endcan
                        @can('view divisions')
                             <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.divisions.*') ? 'active' : '' }}" href="{{ route('admin.divisions.index') }}">
                                    <span>Manajemen Divisi</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endif
        
        @can('validate reports')
            <li class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Validasi Laporan</span>
                </a>
            </li>
        @endcan

        @role('Project Manager')
            <li class="nav-title">Proyek</li>
            
            @can('view projects')
                <li class="nav-item">
                    <a href="{{ route('pm.projects.index') }}" class="nav-link {{ request()->routeIs('pm.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Daftar Proyek</span>
                    </a>
                </li>
            @endcan

            @can('view pm tasks')
                 <li class="nav-item">
                    <a href="{{ route('pm.tasks.index') }}" class="nav-link {{ request()->routeIs('pm.tasks.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Manajemen Tugas</span>
                    </a>
                </li>
            @endcan
            
            @can('view pm reports')
                <li class="nav-item">
                    <a href="{{ route('pm.reports.index') }}" class="nav-link {{ request()->routeIs('pm.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Report</span>
                    </a>
                </li>
            @endcan
        @endrole
        
        @hasanyrole('Developer|QA|UI/UX Designer|Data Analyst')
            <li class="nav-title">Member</li>

            @can('view own tasks')
                <li class="nav-item">
                    <a href="{{ route('teammember.tasks.index') }}" class="nav-link {{ request()->routeIs('teammember.tasks.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tugas Saya</span>
                        @if (isset($taskNotificationCount) && $taskNotificationCount > 0)
                            <span class="badge rounded-pill bg-danger text-white ms-auto">{{ $taskNotificationCount }}</span>
                        @endif
                    </a>
                </li>
            @endcan

            @can('view assigned projects')
                <li class="nav-item">
                    <a href="{{ route('teammember.projects.index') }}" class="nav-link {{ request()->routeIs('teammember.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-folder"></i>
                        <span>Project</span>
                    </a>
                </li>
            @endcan
        @endhasanyrole

    </ul>
</aside>