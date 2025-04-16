<x-admin>
    @section('title')
    {{ 'Dashboard' }}
    @endsection

    <div class="row">
        <div class="col-lg-12 col-12 my-2">
            <div class="d-flex justify-content-center align-items-center mb-3">
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

    @if(auth()->user()->isSuperAdmin())
    <!-- Modal -->
    <div class="modal fade" id="editAppNameModal" tabindex="-1" aria-labelledby="editAppNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('app-name.update') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAppNameModalLabel">Edit App Name</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="app_name" class="form-control" value="{{ config('app.name') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif


    @section('page_scripts')
    <script>
    </script>
    @endsection
</x-admin>