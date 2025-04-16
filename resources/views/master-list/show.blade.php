<x-admin>
    @section('title')
    {{ 'Master List' }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="mb-3 dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    Show/Hide Game Info Columns
                </button>
                <ul class="dropdown-menu p-3" style="min-width: 250px;">
                    <li>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="toggle-all-columns" checked>
                            <label class="form-check-label" for="toggle-all-columns">Toggle All</label>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="2" id="col-castle" checked>
                            <label class="form-check-label" for="col-castle">Castle Level</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="3" id="col-range" checked>
                            <label class="form-check-label" for="col-range">Range Level</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="4" id="col-stables" checked>
                            <label class="form-check-label" for="col-stables">Stables Level</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="5" id="col-barracks" checked>
                            <label class="form-check-label" for="col-barracks">Barracks Level</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="6" id="col-badges" checked>
                            <label class="form-check-label" for="col-badges">Duke Badges</label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped datatable-alliance" width="100%">
                <thead>
                    <tr>
                        <th>Player Name</th>
                        <th>Overall LEVEL</th>
                        <th>Castle Level</th>
                        <th>Range Level</th>
                        <th>Stables Level</th>
                        <th>Barracks Level</th>
                        <th>Duke Badges</th>
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
                responsive: true,
                scrollX: true,
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
                        data: 'overall_level',
                        name: 'overall_level'
                    },
                    {
                        data: 'castle_level',
                        name: 'castle_level',
                        visible: true
                    },
                    {
                        data: 'range_level',
                        name: 'range_level',
                        visible: true
                    },
                    {
                        data: 'stables_level',
                        name: 'stables_level',
                        visible: true
                    },
                    {
                        data: 'barracks_level',
                        name: 'barracks_level',
                        visible: true
                    },
                    {
                        data: 'duke_badges',
                        name: 'duke_badges',
                        visible: true
                    },
                    {
                        data: 'total_needed',
                        name: 'total_needed',
                        orderable: false,
                    },
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 20,
            };
            let table = $('.datatable-alliance').DataTable(dtOverrideGlobals);

            $('.toggle-column').on('change', function() {
                let columnIdx = $(this).data('column');
                table.column(columnIdx).visible($(this).is(':checked'));

                if (!$(this).is(':checked')) {
                    $('#toggle-all-columns').prop('checked', false);
                }

                if ($('.toggle-column:checked').length === $('.toggle-column').length) {
                    $('#toggle-all-columns').prop('checked', true);
                }
            });

            $('#toggle-all-columns').on('change', function() {
                let show = $(this).is(':checked');
                $('.toggle-column').each(function() {
                    $(this).prop('checked', show).trigger('change');
                });
            });
        });
    </script>
    @endsection
</x-admin>