<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Employee</div>

                <a class="nav-link {{ request()->routeIs('founder.dashboard') ? 'active' : '' }}" href="{{ route('founder.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>


            </div>
        </div>

        <!-- Sidebar Footer with Logout -->
        <div class="sb-sidenav-footer">
            {{-- <div class="small">Logged in as: <strong>{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</strong></div> --}}

            <form id="logout-form" action="" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</div>
