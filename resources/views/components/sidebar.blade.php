<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('/') }}" class="nav-link {{ Route::is('/') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    {{ __('pages.dashboard') }}
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('alliance') }}" class="nav-link {{ Route::is('alliance') ? 'active' : '' }}">
                <i class="nav-icon fas fa fa-anchor"></i>
                <p>
                    {{ __('pages.alliance') }}
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('master-list') }}" class="nav-link {{ Route::is('master-list') ? 'active' : '' }}">
                <i class="nav-icon fas fa fa-archive"></i>
                <p>
                    {{ __('pages.master_list') }}
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('distribution') }}" class="nav-link {{ Route::is('distribution') ? 'active' : '' }}">
                <i class="nav-icon fas fa fa-gift"></i>
                <p>
                    {{ __('pages.distribution_list') }}
                </p>
            </a>
        </li>
        @if(auth()->user()->isSuperAdmin())
        <li class="nav-item">
            <a href="{{ route('users') }}" class="nav-link {{ Route::is('users') ? 'active' : '' }}">
                <i class="nav-icon fas fa fa-gift"></i>
                <p>
                    {{ __('pages.user_list') }}
                </p>
            </a>
        </li>
        @endif
    </ul>
</nav>