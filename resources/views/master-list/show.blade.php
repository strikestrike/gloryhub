<x-admin>
    @section('title')
    {{ __('pages.master_list') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="mb-3 dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    {{ __('pages.toggle_game_info_columns') }}
                </button>
                <ul class="dropdown-menu p-3" style="min-width: 250px;">
                    <li>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="toggle-all-columns" checked>
                            <label class="form-check-label" for="toggle-all-columns">{{ __('pages.toggle_all') }}</label>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="2" id="col-castle" checked>
                            <label class="form-check-label" for="col-castle">{{ __('pages.castle_level') }}</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="3" id="col-range" checked>
                            <label class="form-check-label" for="col-range">{{ __('pages.range_level') }}</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="4" id="col-stables" checked>
                            <label class="form-check-label" for="col-stables">{{ __('pages.stables_level') }}</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="5" id="col-barracks" checked>
                            <label class="form-check-label" for="col-barracks">{{ __('pages.barracks_level') }}</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input toggle-column" type="checkbox" data-column="6" id="col-badges" checked>
                            <label class="form-check-label" for="col-badges">{{ __('pages.duke_badges') }}</label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped datatable-alliance" width="100%">
                <thead>
                    <tr>
                        <th>{{ __('pages.player_name') }}</th>
                        <th>{{ __('pages.overall_level') }}</th>
                        <th>{{ __('pages.castle_level') }}</th>
                        <th>{{ __('pages.range_level') }}</th>
                        <th>{{ __('pages.stables_level') }}</th>
                        <th>{{ __('pages.barracks_level') }}</th>
                        <th>{{ __('pages.duke_badges') }}</th>
                        <th>{{ __('pages.duke_needed') }}</th>
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
                        name: 'name',
                        render: function(data, type, row) {
                            return row.castle_name + ' - ' + row.alliance;
                        }
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