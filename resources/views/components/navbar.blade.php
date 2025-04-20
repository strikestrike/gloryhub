<style>
    .dropdown-menu:hover {
        display: block;
    }

    .navbar-nav .dropdown-menu {
        transition: all 0.2s ease-in-out;
    }

    .navbar-nav .dropdown-menu .dropdown-item:hover {
        background-color: #f4f4f4;
        color: #007bff;
    }
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->


        <!-- Languages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                🌐 {{ config('game.languages')[app()->getLocale()] }}
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="langDropdown">
                @foreach(config('game.languages') as $lang => $langName)
                <a class="dropdown-item" href="{{ route('change.language', $lang) }}">
                    {{ $langName }}
                </a>
                @endforeach
            </div>
        </li>
        <!-- User Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="image mr-2">
                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                        alt="User Image" width="30" height="30">
                </div>
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user mr-2"></i> {{ __('pages.profile') }}
                </a>
                <a class="dropdown-item" href="{{ route('game-data.edit') }}">
                    <i class="fas fa-gamepad mr-2"></i> {{ __('pages.game_data') }}
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('pages.logout') }}
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>