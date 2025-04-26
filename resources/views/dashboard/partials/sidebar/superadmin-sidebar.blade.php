<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Superadmin</div>

                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link {{ request()->routeIs('vans.index') ? 'active' : '' }}" href="{{ route('vans.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Vans
                </a>

                <a class="nav-link {{ request()->routeIs('vans.showSelection') ? 'active' : '' }}" href="{{ route('vans.showSelection') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Van Selection
                </a>

                <a class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Employee
                </a>

                <a class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Departments
                </a>

                <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Categories
                </a>

                <a class="nav-link {{ request()->routeIs('units.index') ? 'active' : '' }}" href="{{ route('units.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Units
                </a>

                <a class="nav-link {{ request()->routeIs('items.index') ? 'active' : '' }}" href="{{ route('items.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Items
                </a>

                <a class="nav-link {{ request()->routeIs('taxes.index') ? 'active' : '' }}" href="{{ route('taxes.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Taxes
                </a>

                <a class="nav-link {{ request()->routeIs('taxes.index') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Customers
                </a>










            </div>
        </div>

        <!-- Sidebar Footer with Logout -->
        <div class="sb-sidenav-footer">
            {{-- <div class="small">Logged in as: <strong>{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</strong></div> --}}

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</div>
