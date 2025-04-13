<x-admin>
    @section('title')
    {{ __('Game Information Setup') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <h4>{{ $gameData ? 'Edit' : 'Create' }} Game Data</h4>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Left Column: Form --}}
                <div class="col-md-6 border-end pe-4">
                    @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('game-data.store') }}">
                        @csrf

                        {{-- Alliance Name --}}
                        <div class="form-group">
                            <label class="required" for="alliance">Alliance Name</label>
                            <input
                                type="text"
                                id="alliance"
                                name="alliance"
                                class="form-control {{ $errors->has('alliance') ? 'is-invalid' : '' }}"
                                value="{{ old('alliance', $gameData->alliance ?? '') }}"
                                {{ ($gameData->alliance ?? null) ? 'readonly' : 'required' }}>
                            @if($errors->has('alliance'))
                            <span class="text-danger">{{ $errors->first('alliance') }}</span>
                            @endif
                            <span class="help-block">Alliance name cannot be changed after setup</span>
                        </div>

                        {{-- Military Buildings --}}
                        <div class="row">
                            @foreach(config('game.buildings') as $type => $label)
                            <div class="form-group col-md-6">
                                <label for="{{ $type }}_level">{{ $label }} Level</label>
                                <select
                                    id="{{ $type }}_level"
                                    name="{{ $type }}_level"
                                    class="form-control {{ $errors->has($type.'_level') ? 'is-invalid' : '' }}"
                                    required>
                                    @foreach(config('game.building_levels') as $level)
                                    <option value="{{ $level }}"
                                        {{ old("{$type}_level", $gameData->{"{$type}_level"} ?? config('game.building_levels')[0]) == $level ? 'selected' : '' }}>
                                        Lv. {{ $level }}
                                    </option>
                                    @endforeach
                                </select>
                                @if($errors->has("{$type}_level"))
                                <span class="text-danger">{{ $errors->first("{$type}_level") }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        {{-- Duke Badges --}}
                        <div class="form-group">
                            <label for="duke_badges">Duke Badges</label>
                            <input
                                type="number"
                                id="duke_badges"
                                name="duke_badges"
                                min="0"
                                class="form-control {{ $errors->has('duke_badges') ? 'is-invalid' : '' }}"
                                value="{{ old('duke_badges', $gameData->duke_badges ?? 0) }}"
                                required>
                            @if($errors->has('duke_badges'))
                            <span class="text-danger">{{ $errors->first('duke_badges') }}</span>
                            @endif
                        </div>

                        {{-- Submit --}}
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ $gameData ? 'Update' : 'Create' }} Game Data
                            </button>
                        </div>

                        <div class="text-muted small text-center mt-3">
                            Levels range from {{ config('game.building_levels.0') }} to {{ config('game.building_levels')[count(config('game.building_levels')) - 1] }}<br>
                            Alliance name is set once and cannot be modified later.
                        </div>
                    </form>
                </div>

                {{-- Right Column: Duke Level Requirements --}}
                <div class="col-md-6 ps-4">
                    <h5 class="mb-3">Duke Level Requirements</h5>

                    @if(isset($dukeLevels) && $dukeLevels->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Level</th>
                                    <th>Castle</th>
                                    <th>Range</th>
                                    <th>Stables</th>
                                    <th>Barracks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dukeLevels as $level => $requirements)
                                <tr>
                                    <td>{{ $level }}</td>
                                    <td>{{ $requirements->castle }}</td>
                                    <td>{{ $requirements->range }}</td>
                                    <td>{{ $requirements->stables }}</td>
                                    <td>{{ $requirements->barracks }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No requirements found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin>