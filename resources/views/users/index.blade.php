<x-admin>
    @section('title', 'Users Management')

    <div class="card">
        <div class="card-header">
            <h4>User List</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped datatable-users" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Alliance</th>
                        <th>Registered At</th>
                        <th>Actions</th>
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
                            <button class="btn btn-warning btn-sm btn-reset" data-id="${id}">Reset Password</button>
                            <button class="btn btn-danger btn-sm btn-delete ml-2" data-id="${id}">Delete</button>
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
                if (confirm('Reset password for this user?')) {
                    $.post("{{ url('/users') }}/" + userId + "/reset-password", {
                        _token: '{{ csrf_token() }}'
                    }).done(function(response) {
                        alert(response.message || 'Password reset successfully.');
                    }).fail(function() {
                        alert('Failed to reset password.');
                    });
                }
            });

            // Handle delete via AJAX
            $(document).on('click', '.btn-delete', function() {
                const userId = $(this).data('id');
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: "{{ url('/users') }}/" + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message || 'User deleted successfully.');
                            dtUsers.ajax.reload();
                        },
                        error: function() {
                            alert('Failed to delete user.');
                        }
                    });
                }
            });
        });
    </script>
    @endsection
</x-admin>