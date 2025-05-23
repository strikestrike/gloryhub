<x-admin>
    @section('title')
    {{ (auth()->user()->isSuperAdmin() || auth()->user()->isKing()) ? __('pages.alliance') : __('pages.alliance') . ' ' . auth()->user()->alliance->name }}
    @endsection

    <div class="card">
        <!-- <div class="card-header d-flex align-items-center">
            <span class="mr-3">Target Level</span>
            <select id="targetLevel" class="form-control col-2">
                @for($i = config('game.min_level'); $i <= config('game.max_level'); $i++)
                    <option value="{{ $i }}">Lv {{ $i }}</option>
                    @endfor
            </select>
            <span class="mr-3 ml-3">Building Type</span>
            <select id="buildingType" class="form-control col-2">
                <option value="all">All</option>
                @foreach(config('game.buildings') as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div> -->

        <div class="card-body">
            <table class="table table-striped datatable-alliance" width="100%">
                <thead>
                    <tr>
                        <th>{{ __('pages.player_name') }}</th>
                        <th>{{ __('pages.castle_level') }}</th>
                        <th>{{ __('pages.range') }}</th>
                        <th>{{ __('pages.stables') }}</th>
                        <th>{{ __('pages.barracks') }}</th>
                        <th>{{ __('pages.duke_badges') }}</th>
                        <th>{{ __('pages.target_building') }}</th>
                        <th>{{ __('pages.target_level') }}</th>
                        <th>{{ __('pages.updated_at') }}</th>
                        <th>{{ __('pages.dukes_castle_needed') }}</th>
                        <th>{{ __('pages.dukes_stables_needed') }}</th>
                        <th>{{ __('pages.dukes_barracks_needed') }}</th>
                        <th>{{ __('pages.dukes_range_needed') }}</th>
                        <th>{{ __('pages.dukes_needed') }}</th>
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
                    url: "{{ route('alliance.data') }}",
                    data: function(d) {
                        d.targetLevel = $('#targetLevel').val();
                        d.buildingType = $('#buildingType').val();
                    }
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
                        data: 'range_level',
                        name: 'range_level'
                    },
                    {
                        data: 'stables_level',
                        name: 'stables_level'
                    },
                    {
                        data: 'barracks_level',
                        name: 'barracks_level'
                    },
                    {
                        data: 'duke_badges',
                        name: 'duke_badges'
                    },
                    {
                        data: 'target_building',
                        name: 'target_building'
                    },
                    {
                        data: 'target_level',
                        name: 'target_level'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        render: function(data, type, row) {
                            return dayjs(data).format('MMM D, YYYY h:mm A');
                        }
                    },
                    {
                        data: 'castle_needed',
                        name: 'castle_needed',
                        orderable: false,
                    },
                    {
                        data: 'stables_needed',
                        name: 'stables_needed',
                        orderable: false,
                    },
                    {
                        data: 'barracks_needed',
                        name: 'barracks_needed',
                        orderable: false,
                    },
                    {
                        data: 'range_needed',
                        name: 'range_needed',
                        orderable: false,
                    },
                    {
                        data: 'total_needed',
                        name: 'total_needed',
                        orderable: false,
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 20,
            };
            let table = $('.datatable-alliance').DataTable(dtOverrideGlobals);

            $('#targetLevel, #buildingType').on('change', function() {
                table.ajax.reload();
            });

            $('#targetLevel').val(50).trigger('change');
        });
    </script>
    @endsection
</x-admin>