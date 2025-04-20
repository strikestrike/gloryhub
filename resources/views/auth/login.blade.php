<x-guest-layout>
    @section('title')
    {{ __('pages.log_in') }}
    @endsection
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">{{ __('pages.sign_in_prompt') }}</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email" class="form-control" type="email" name="email" :value="old('email')"
                            required autofocus autocomplete="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="input-group mb-3">
                        <input id="password" class="form-control" type="password" name="password" required
                            autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember">
                                    {{ __('pages.remember_me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('pages.sign_in') }}</button>
                        </div>
                    </div>
                </form>

                <p class="mb-1">
                    <a href="{{ route('password.request') }}">{{ __('pages.forgot_password') }}</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">{{ __('pages.register_new_account') }}</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>