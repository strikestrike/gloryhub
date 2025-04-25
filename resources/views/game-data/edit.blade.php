<x-admin>
    @section('title')
    {{ __('pages.game_info_setup') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <h4>{{ $gameData ? __('pages.edit') : __('pages.create') }} {{ __('pages.game_data') }}</h4>
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

                        @if(request()->has('fresh'))
                        <input type="hidden" name="fresh" value="1">
                        @endif

                        {{-- Castle Name --}}
                        <div class="form-group">
                            <label class="required" for="castle_name">{{ __('pages.castle_name') }}</label>
                            <input
                                type="text"
                                id="castle_name"
                                name="castle_name"
                                class="form-control {{ $errors->has('castle_name') ? 'is-invalid' : '' }}"
                                value="{{ old('castle_name', $gameData->castle_name ?? '') }}"
                                required>
                            @if($errors->has('castle_name'))
                            <span class="text-danger">{{ $errors->first('castle_name') }}</span>
                            @endif
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
                            <label for="duke_badges">{{ __('pages.duke_badges') }}</label>
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

                        <div class="row">
                            {{-- Target Building --}}
                            <div class="form-group col-md-6">
                                <label for="target_building">{{ __('pages.target_building') }}</label>
                                <select
                                    id="target_building"
                                    name="target_building"
                                    class="form-control {{ $errors->has('target_building') ? 'is-invalid' : '' }}"
                                    required>
                                    @foreach(array_merge(['overall' => 'Overall'], config('game.buildings')) as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('target_building', $gameData->target_building ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @if($errors->has('target_building'))
                                <span class="text-danger">{{ $errors->first('target_building') }}</span>
                                @endif
                            </div>
                            {{-- Target Level --}}
                            <div class="form-group col-md-6">
                                <label for="target_level">{{ __('pages.target_level') }}</label>
                                <select
                                    id="target_level"
                                    name="target_level"
                                    class="form-control {{ $errors->has('target_level') ? 'is-invalid' : '' }}"
                                    required>
                                    @foreach(config('game.building_levels') as $level)
                                    <option value="{{ $level }}"
                                        {{ old('target_level', $gameData->target_level ?? '') == $level ? 'selected' : '' }}>
                                        Lv. {{ $level }}
                                    </option>
                                    @endforeach
                                </select>
                                @if($errors->has('target_level'))
                                <span class="text-danger">{{ $errors->first('target_level') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ isset($gameData) && $gameData?->exists ? __('pages.update') : __('pages.create') }} {{ __('pages.game_data') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Right Column: Duke Level Requirements --}}
                <div class="col-md-6 ps-4">
                    <h5 class="mb-3">{{ __('pages.duke_level_requirements') }}</h5>

                    @if(isset($dukeLevels) && $dukeLevels->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('pages.level') }}</th>
                                    <th>{{ __('pages.castle') }}</th>
                                    <th>{{ __('pages.range') }}</th>
                                    <th>{{ __('pages.stables') }}</th>
                                    <th>{{ __('pages.barracks') }}</th>
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
                    <p class="text-muted">{{ __('pages.no_requirements_found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin>