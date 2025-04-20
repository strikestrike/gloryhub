<x-guest-layout>
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Complete Registration</p>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-group mb-3">
                        <input type="text" name="player_name" class="form-control" value="{{ old('player_name', $player_name) }}" required placeholder="Player Name">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required readonly placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="kingdom" class="form-control" value="{{ old('kingdom', $kingdom) }}" required placeholder="Kingdom">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-crown"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="alliance" class="form-control" value="{{ old('alliance', $alliance) }}" required placeholder="Alliance">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-users"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" required placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>