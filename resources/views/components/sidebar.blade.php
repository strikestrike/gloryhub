<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('/') }}" class="nav-link {{ Route::is('/') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('alliance') }}" class="nav-link {{ Route::is('alliance') ? 'active' : '' }}">
                <i class="nav-icon fas fa fa-anchor"></i>
                <p>
                    Alliance
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('master-list') }}" class="nav-link {{ Route::is('master-list') ? 'active' : '' }}">
                <i class="nav-icon fas fa fa-archive"></i>
                <p>
                    Master List
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('distribution') }}" class="nav-link {{ Route::is('distribution') ? 'active' : '' }}">
                <i class="nav-icon fas fa fa-gift"></i>
                <p>
                    Distribution List
                </p>
            </a>
        </li>
    </ul>
</nav>