<x-admin>
    @section('title', __('pages.users_management'))

    <div class="card">
        <div class="card-header">
            <h4>{{ __('pages.user_list') }}</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped datatable-users" width="100%">
                <thead>
                    <tr>
                        <th>{{ __('pages.name') }}</th>
                        <th>{{ __('pages.email') }}</th>
                        <th>{{ __('pages.role') }}</th>
                        <th>{{ __('pages.alliance') }}</th>
                        <th>{{ __('pages.registered_at') }}</th>
                        <th>{{ __('pages.actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @section('page_scripts')
    <script type="text/javascript">
        $(function() {
            let dtUsers = $('.datatable-users').DataTable({
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.data') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'alliance',
                        name: 'alliance',
                        orderable: false,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return dayjs(data).format('MMM D, YYYY h:mm A');
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(id, type, row, meta) {
                            return `
                            <button class="btn btn-warning btn-sm btn-reset" data-id="${id}">{{ __('global.reset_password') }}</button>
                            <button class="btn btn-danger btn-sm btn-delete ml-2" data-id="${id}">{{ __('pages.delete') }}</button>
                        `;
                        }
                    }
                ],
                order: [
                    [4, 'desc']
                ]
            });

            // Optional: handle actions via AJAX or confirm dialogs
            $(document).on('click', '.btn-reset', function() {
                const userId = $(this).data('id');
                if (confirm("{{ __('pages.confirm_reset_password') }}")) {
                    $.post("{{ url('/users') }}/" + userId + "/reset-password", {
                        _token: '{{ csrf_token() }}'
                    }).done(function(response) {
                        alert(response.message || "{{ __('pages.password_reset_success') }}");
                    }).fail(function() {
                        alert("{{ __('pages.password_reset_failed') }}");
                    });
                }
            });

            // Handle delete via AJAX
            $(document).on('click', '.btn-delete', function() {
                const userId = $(this).data('id');
                if (confirm("{{ __('pages.confirm_delete_user') }}")) {
                    $.ajax({
                        url: "{{ url('/users') }}/" + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message || "{{ __('pages.user_deleted_successfully') }}");
                            dtUsers.ajax.reload();
                        },
                        error: function() {
                            alert("{{ __('pages.failed_delete_user') }}");
                        }
                    });
                }
            });
        });
    </script>
    @endsection
</x-admin>