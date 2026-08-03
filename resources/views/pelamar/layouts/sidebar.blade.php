<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">

        <div class="user-panel mt-3 pb-2 mb-3 d-flex align-items-center">
            <div class="image">
                {{-- <img src="{{ asset('favicon.png') }}" class="img-circle elevation-2"
                    style="width:30px; height:30px; object-fit:cover;" alt="Logo"> --}}
            </div>
            <div class="info ml-2">
                <a href="{{ url('/home') }}" class="d-block font-weight-bold text-light">Recruitment WMU GTT</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('home') }}"
                        class="nav-link {{ request()->is('home*') ? 'active' : '' }} text-light">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('datadiri') }}"
                        class="nav-link {{ request()->is('datadiri*') ? 'active' : '' }} text-light">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Data Diri</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('loker') }}"
                        class="nav-link {{ request()->is('loker*') ? 'active' : '' }} text-light">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Loker</p>
                    </a>
                </li>

                <!-- Item -->
                <li class="nav-item">
                    <a href="{{ url('lamaran') }}"
                        class="nav-link {{ request()->is('lamaran*') ? 'active' : '' }} text-light">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Lamaran</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
