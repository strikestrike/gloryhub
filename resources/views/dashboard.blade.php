<x-admin>
    @section('title')
    {{ 'Dashboard' }}
    @endsection

    <div class="row">
        <div class="col-lg-12 col-12 my-2">
            <button type="button" class="btn btn-default float-right" id="daterange-btn">
                <i class="far fa-calendar-alt"></i> <span></span>
                <i class="fas fa-caret-down"></i>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalAwards ?? 0 }}</h3>
                    <p>Total Awards Assigned</p>
                </div>
                <div class="icon">
                    <i class="fas fa-medal"></i>
                </div>
                <a href="{{ route('distribution') }}" class="small-box-footer">
                    View Assignments <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalPlayers ?? 0 }}</h3>
                    <p>Total Players</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('alliance') }}" class="small-box-footer">
                    View Players <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    @section('page_scripts')
    <script>
    </script>
    @endsection
</x-admin>