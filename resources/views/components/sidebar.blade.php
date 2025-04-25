<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('/') }}" class="nav-link {{ Route::is('/') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>{{ __('pages.dashboard') }}</p>
            </a>
        </li>

        <!-- Master List -->
        <li class="nav-item">
            <a href="{{ route('master-list') }}" class="nav-link {{ Route::is('master-list') ? 'active' : '' }}">
                <i class="nav-icon fas fa-archive"></i>
                <p>{{ __('pages.master_list') }}</p>
            </a>
        </li>

        <!-- Distribution List -->
        <li class="nav-item">
            <a href="{{ route('distribution') }}" class="nav-link {{ Route::is('distribution') ? 'active' : '' }}">
                <i class="nav-icon fas fa-gift"></i>
                <p>{{ __('pages.distribution_list') }}</p>
            </a>
        </li>

        @if(auth()->user()->isSuperAdmin())
        <!-- User List -->
        <li class="nav-item">
            <a href="{{ route('users') }}" class="nav-link {{ Route::is('users') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>{{ __('pages.user_list') }}</p>
            </a>
        </li>

        <!-- Access Requests -->
        <li class="nav-item">
            <a href="{{ route('access-requests') }}" class="nav-link {{ Route::is('access-requests') ? 'active' : '' }}">
                <i class="nav-icon fas fa-key"></i>
                <p>{{ __('pages.access_requests') }}</p>
            </a>
        </li>
        @endif
    </ul>
</nav>