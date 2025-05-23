<x-admin>
    @section('title')
    {{ __('pages.award_distribution') }}
    @endsection

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="mr-3">{{ __('pages.kingdom_level') }}</span>
            <select id="kingdomLevel" class="form-control col-2">
                @foreach(range(config('game.kingdom_levels.min'), config('game.kingdom_levels.max')) as $level)
                <option value="{{ $level }}">Level {{ $level }}</option>
                @endforeach
            </select>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isKing())
            <button type="button" class="btn btn-danger ml-3" id="reset-all-btn">
                {{ __('pages.reset_assignments') }}
            </button>
            <button type="button" class="btn btn-success ml-3" id="saveDistributionBtn">
                {{ __('pages.save') }}
            </button>
            @endif
        </div>

        <div class="card-body">
            <table class="table table-sm table-striped" id="distributionTable">
                <thead>
                    <tr>
                        <th>{{ __('pages.award') }}</th>
                        <th>{{ __('pages.player') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @section('page_scripts')
    <script>
        const awardTranslations = {
            crown: '{{ __("pages.Crown") }}',
            conqueror: '{{ __("pages.Conqueror") }}',
            defender: '{{ __("pages.Defender") }}',
            support: '{{ __("pages.Support") }}',
        };

        function renderTable(data, players) {
            const tbody = $('#distributionTable tbody');
            tbody.empty();

            data.forEach((row) => {
                const options = players.map(player => {
                    return `<option value="${player.game_data_id}" ${player.game_data_id === row.game_data_id ? 'selected' : ''}>
                                ${player.castle_name} - ${player.alliance ?? ''} (${player.total_needed})
                            </option>`;
                }).join('');

                const awardTypeText = awardTranslations[row.award_type.toLowerCase()] || row.award_type;
                tbody.append(`
                    <tr class="award-row" data-type="${row.type}" data-position="${row.position}">
                        <td>${awardTypeText} #${row.position + 1}</td>
                        <td>
                            <select class="form-control award-select" {{ !(auth()->user()->isSuperAdmin() || auth()->user()->isKing()) ? 'disabled' : '' }}>
                                <option value="">-- {{ __('pages.select_player') }} --</option>
                                ${options}
                            </select>
                        </td>
                    </tr>
                `);
            });
        }

        $(function() {
            function loadDistribution() {
                $.get("{{ route('distribution.data') }}", {
                    kingdomLevel: $('#kingdomLevel').val()
                }, function(response) {
                    renderTable(response.awards, response.players);
                });
            }

            $('#kingdomLevel').on('change', loadDistribution);
            loadDistribution();

            $('#saveDistributionBtn').on('click', function() {
                const assignments = [];

                $('.award-row').each(function() {
                    const $row = $(this);
                    const type = $row.data('type');
                    const position = $row.data('position');
                    const gameDataId = $row.find('.award-select').val(); // <- updated class here

                    if (gameDataId) {
                        assignments.push({
                            type: type.toLowerCase(),
                            game_data_id: gameDataId,
                            position: parseInt(position),
                        });
                    }
                });

                console.log('assignments', assignments);

                $.ajax({
                    url: "{{ route('distribution.save.assignment.bulk') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        assignments: assignments,
                        kingdom_level: $('#kingdomLevel').val()
                    },
                    success: function() {
                        alert("{{ __('pages.assignments_saved') }}");
                    },
                    error: function() {
                        alert("{{ __('pages.save_assignments_failed') }}");
                    }
                });
            });

            $('#reset-all-btn').on('click', function() {
                if (!confirm("{{ __('pages.confirm_reset_all_assignments') }}")) return;

                const kingdomLevel = $('#kingdomLevel').val();

                $.ajax({
                    url: "{{ route('distribution.reset.all') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kingdom_level: kingdomLevel,
                    },
                    success: function() {
                        $('.player-select').val('');
                        console.log("{{ __('pages.all_assignments_reset') }}");
                    },
                    error: function() {
                        alert("{{ __('pages.reset_all_assignments_failed') }}");
                    }
                });
            });
        });
    </script>
    @endsection

</x-admin>