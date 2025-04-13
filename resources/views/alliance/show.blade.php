<x-admin>
    @section('title')
    {{ 'Alliance ' . auth()->user()->alliance->name }}
    @endsection

    <div class="card">
        <div class="card-header d-flex align-items-center">
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
        </div>

        <div class="card-body">
            <table class="table table-striped datatable-alliance" width="100%">
                <thead>
                    <tr>
                        <th>Player Name</th>
                        <th>CASTLE LEVEL</th>
                        <th>RANGE</th>
                        <th>STABLES</th>
                        <th>BARRACKS</th>
                        <th>DUKE BADGES</th>
                        <th>Date updated</th>
                        <th>Dukes-Castle level goal</th>
                        <th>Dukes-Stables needed</th>
                        <th>Dukes-Barracks needed</th>
                        <th>Dukes-Range needed</th>
                        <th>Dukes needed</th>
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
                        data: 'updated_at',
                        name: 'updated_at',
                        render: function(data, type, row) {
                            return dayjs(data).format('MMM D, YYYY h:mm A');
                        }
                    },
                    {
                        data: 'castle_needed',
                        name: 'castle_needed'
                    },
                    {
                        data: 'stables_needed',
                        name: 'stables_needed'
                    },
                    {
                        data: 'barracks_needed',
                        name: 'barracks_needed'
                    },
                    {
                        data: 'range_needed',
                        name: 'range_needed'
                    },
                    {
                        data: 'total_needed',
                        name: 'total_needed'
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