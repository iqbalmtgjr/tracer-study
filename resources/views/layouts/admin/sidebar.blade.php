<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="brand-link">
        <img src="{{ asset('asset/img/icon/stkip.png') }}" alt="STKIP Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Tracer Study</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('profile') }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @role('admin')
                    <li class="nav-header">MASTER DATA</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.program-studi.index') }}"
                            class="nav-link {{ request()->routeIs('admin.program-studi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>Program Studi</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.alumni.index') }}"
                            class="nav-link {{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Alumni</p>
                        </a>
                    </li>

                    <li class="nav-header">KUESIONER</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.kuesioner.index') }}"
                            class="nav-link {{ request()->routeIs('admin.kuesioner.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Kelola Kuesioner</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.index') }}"
                            class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Laporan & Statistik</p>
                        </a>
                    </li>
                @endrole

                @role('alumni')
                    <li class="nav-item">
                        <a href="{{ route('alumni.profil') }}"
                            class="nav-link {{ request()->routeIs('alumni.profil') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('alumni.kuesioner') }}"
                            class="nav-link {{ request()->routeIs('alumni.kuesioner') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Isi Kuesioner</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('alumni.riwayat') }}"
                            class="nav-link {{ request()->routeIs('alumni.riwayat') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Kuesioner</p>
                        </a>
                    </li>
                @endrole
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
