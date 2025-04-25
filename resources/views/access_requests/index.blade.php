<x-admin>
    @section('title')
    {{ __('pages.access_requests') }}
    @endsection

    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable-access-request" width="100%">
                <thead>
                    <tr>
                        <th>{{ __('pages.player_name') }}</th>
                        <th>{{ __('pages.kingdom') }}</th>
                        <th>{{ __('pages.email') }}</th>
                        <th>{{ __('pages.status') }}</th>
                        <th>{{ __('pages.actions') }}</th>
                        <th>{{ __('pages.updated_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    @section('page_scripts')
    <script type="text/javascript">
        $(function() {
            let dtOverrideGlobals = {
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                retrieve: true,
                ajax: {
                    url: "{{ route('access-requests.data') }}",
                    data: function(d) {}
                },
                columns: [{
                        data: 'player_name',
                        name: 'player_name'
                    },
                    {
                        data: 'kingdom',
                        name: 'kingdom'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return data === 'approved' ? '<span class="badge badge-success">Approved</span>' :
                                data === 'denied' ? '<span class="badge badge-danger">Denied</span>' :
                                '<span class="badge badge-warning">Pending</span>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-success approve-btn" data-id="${row.id}">Approve</button>
                                <button class="btn btn-danger deny-btn" data-id="${row.id}">Deny</button>
                                <button class="btn btn-outline-danger delete-btn" data-id="${row.id}">Delete</button>
                            `;
                        }
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        render: function(data, type, row) {
                            return dayjs(data).format('MMM D, YYYY h:mm A');
                        }
                    }
                ],
                order: [
                    [5, 'desc']
                ],
                pageLength: 20,
            };
            let table = $('.datatable-access-request').DataTable(dtOverrideGlobals);

            $(document).on('click', '.approve-btn', function() {
                let id = $(this).data('id');
                $.post("{{ url('/access-requests') }}/" + id + "/approve", {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    table.ajax.reload();
                }).fail(function() {
                    alert('Error approving the request.');
                });
            });

            $(document).on('click', '.deny-btn', function() {
                let id = $(this).data('id');
                $.post("{{ url('/access-requests') }}/" + id + "/deny", {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    table.ajax.reload();
                }).fail(function() {
                    alert('Error denying the request.');
                });
            });

            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');

                if (confirm('Are you sure you want to delete this access request?')) {
                    $.ajax({
                        url: "{{ url('/access-requests') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.ajax.reload();
                        },
                        error: function() {
                            alert('Error deleting the request.');
                        }
                    });
                }
            });

        });
    </script>
    @endsection
</x-admin>