<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Superadmin</div>

                <!-- Dashboard -->
                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <!-- Van Management Accordion -->
                <a class="nav-link collapsed {{ request()->routeIs('vans.index', 'vans.showSelection') ? 'active' : '' }}"
                   href="#"
                   data-bs-toggle="collapse"
                   data-bs-target="#vanMenu"
                   aria-expanded="{{ request()->routeIs('vans.index', 'vans.showSelection') ? 'true' : 'false' }}"
                   aria-controls="vanMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-shuttle-van"></i></div>
                    Van Management
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->routeIs('vans.index', 'vans.showSelection') ? 'show' : '' }}" id="vanMenu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('vans.index') ? 'active' : '' }}" href="{{ route('vans.index') }}">Vans</a>
                        <a class="nav-link {{ request()->routeIs('vans.showSelection') ? 'active' : '' }}" href="{{ route('vans.showSelection') }}">Van Selection</a>
                    </nav>
                </div>

                <!-- Employees -->
                <a class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                    Employees
                </a>

                <!-- Departments -->
                <a class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Departments
                </a>

                <!-- Categories -->
                <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                    Categories
                </a>

                <!-- Units -->
                <a class="nav-link {{ request()->routeIs('units.index') ? 'active' : '' }}" href="{{ route('units.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-cubes"></i></div>
                    Units
                </a>
                <a class="nav-link {{ request()->routeIs('alternative-units.index') ? 'active' : '' }}" href="{{ route('alternative-units.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-cubes"></i></div>
                   alternative Units
                </a>

                <!-- Items -->
                <a class="nav-link {{ request()->routeIs('items.index') ? 'active' : '' }}" href="{{ route('items.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                    Items
                </a>

                <!-- Taxes -->
                <a class="nav-link {{ request()->routeIs('taxes.index') ? 'active' : '' }}" href="{{ route('taxes.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-percent"></i></div>
                    Taxes
                </a>

                <!-- Customers -->
                <a class="nav-link {{ request()->routeIs('customers.index') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Customers
                </a>

                <!-- Reports -->

                <a class="nav-link collapsed {{ request()->routeIs('reports.index', 'customer_report.index','van_report.index','employee_report.index','stock_report.index') ? 'active' : '' }}"
                   href="#"
                   data-bs-toggle="collapse"
                   data-bs-target="#reportMenu"
                   aria-expanded="{{ request()->routeIs('reports.index', 'customer_report.index','van_report.index','employee_report.index','stock_report.index') ? 'true' : 'false' }}"
                   aria-controls="reportMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    Report Management
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->routeIs('reports.index', 'customer_report.index','van_report.index','employee_report.index','stock_report.index') ? 'show' : '' }}" id="reportMenu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">Sale Report</a>
                        <a class="nav-link {{ request()->routeIs('customer_report.index') ? 'active' : '' }}" href="{{ route('customer_report.index') }}">Customer Report</a>
                        <a class="nav-link {{ request()->routeIs('van_report.index') ? 'active' : '' }}" href="{{ route('van_report.index') }}">Van Report</a>
                        <a class="nav-link {{ request()->routeIs('employee_report.index') ? 'active' : '' }}" href="{{ route('employee_report.index') }}">Employee Report</a>
                        <a class="nav-link {{ request()->routeIs('stock_report.index') ? 'active' : '' }}" href="{{ route('stock_report.index') }}">Stock Report</a>
                    </nav>
                </div>

            </div>
        </div>

        <!-- Sidebar Footer with Logout -->
        <div class="sb-sidenav-footer">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</div>
