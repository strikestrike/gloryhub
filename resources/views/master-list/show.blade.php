<x-admin>
    @section('title')
    {{ 'Master List' }}
    @endsection

    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable-alliance" width="100%">
                <thead>
                    <tr>
                        <th>Player Name</th>
                        <th>CASTLE LEVEL</th>
                        <th>Duke Needed</th>
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
                processing: true,
                serverSide: true,
                retrieve: true,
                ajax: {
                    url: "{{ route('masterlist.data') }}"
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'castle_level',
                        name: 'castle_level'
                    },
                    {
                        data: 'duke_needed',
                        name: 'duke_needed'
                    },
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 20,
            };
            let table = $('.datatable-alliance').DataTable(dtOverrideGlobals);
        });
    </script>
    @endsection
</x-admin>