<x-admin>
    @section('title')
    {{ __('pages.dashboard') }}
    @endsection

    <div class="row">
        <div class="col-12 my-3 text-center">
            <h2>
                {{ config('app.name') }}
                @if(auth()->user()->isSuperAdmin())
                <button class="btn btn-sm btn-outline-secondary ml-2" data-toggle="modal" data-target="#editAppNameModal">
                    <i class="fas fa-edit"></i>
                </button>
                @endif
            </h2>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-6 col-md-6 mb-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalAwards ?? 0 }}</h3>
                    <p>{{ __('pages.total_awards_assigned') }}</p>
                </div>
                <div class="icon"><i class="fas fa-medal"></i></div>
                <a href="{{ route('distribution') }}" class="small-box-footer">
                    {{ __('pages.view_assignments') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalPlayers ?? 0 }}</h3>
                    <p>{{ __('pages.total_players') }}</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('alliance') }}" class="small-box-footer">
                    {{ __('pages.view_players') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Player Specific Cards -->
    @if(auth()->user()->isPlayer())
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-center"><strong>Selected Castle:</strong> {{ $castleName }}</h3>
        </div>

        <div class="col-md-4 col-lg-3 col-6 mb-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $castleNeeded }}</h3>
                    <p>{{ __('pages.dukes_castle_needed') }}</p>
                </div>
                <div class="icon"><i class="fas fa-chess-rook"></i></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-6 mb-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stablesNeeded }}</h3>
                    <p>{{ __('pages.dukes_stables_needed') }}</p>
                </div>
                <div class="icon"><i class="fas fa-horse"></i></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-6 mb-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $barracksNeeded }}</h3>
                    <p>{{ __('pages.dukes_barracks_needed') }}</p>
                </div>
                <div class="icon"><i class="fas fa-shield-alt"></i></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-6 mb-3">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $rangeNeeded }}</h3>
                    <p>{{ __('pages.dukes_range_needed') }}</p>
                </div>
                <div class="icon"><i class="fas fa-bullseye"></i></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-6 mb-3">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $totalNeeded }}</h3>
                    <p>{{ __('pages.dukes_needed') }}</p>
                </div>
                <div class="icon"><i class="fas fa-layer-group"></i></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit App Name Modal -->
    @if(auth()->user()->isSuperAdmin())
    <div class="modal fade" id="editAppNameModal" tabindex="-1" aria-labelledby="editAppNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('app-name.update') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('pages.edit_app_name') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="app_name" class="form-control" value="{{ config('app.name') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('pages.save') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('pages.cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    @section('page_scripts')
    <script>
        // Custom scripts can be added here
    </script>
    @endsection
</x-admin>