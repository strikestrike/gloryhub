<x-guest-layout>
    @section('title')
    {{ __('pages.access_requests') }}
    @endsection

    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body">

                <form action="{{ route('questionnaire.submit') }}" method="POST">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" name="kingdom" class="form-control" placeholder="{{ __('pages.kingdom') }}" value="{{ old('kingdom') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-crown"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('kingdom')" class="text-danger" />
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="alliance" class="form-control" placeholder="{{ __('pages.alliance') }}" value="{{ old('alliance') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-users"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('alliance')" class="text-danger" />
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="player_name" class="form-control" placeholder="{{ __('pages.player_name') }}" value="{{ old('player_name') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('player_name')" class="text-danger" />
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('pages.email') }}" value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="text-danger" />
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('pages.save') }}</button>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">{{ __('Already have an account? Login') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>