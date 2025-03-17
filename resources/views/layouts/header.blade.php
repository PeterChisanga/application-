<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark bg-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Centered Brand Name -->
    <ul class="navbar-nav mx-auto">
        <li class="nav-item">
            <span class="nav-link">
                <h3 class="text-white">Swarna Metals</h3>
            </span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo with Retract Cross -->
    <div class="brand-link d-flex align-items-center justify-content-between">
        <p class="text-center flex-grow-1">
            <span class="brand-text font-weight-light">Swarna ERP</span>
        </p>
        <a href="#" class="d-block d-lg-none text-white" data-widget="pushmenu" role="button" aria-label="Close Sidebar">
            <i class="fas fa-times"></i>
        </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboards.admin') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employees.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Employees</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('equipments.index') }}" class="nav-link">
                        <i class="fas fa-truck"></i>
                        <p>Equipment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('applications.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Job Applications</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('not-implemented-yet') }}" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Warehouse/Inventory</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.show') }}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.logout') }}" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>