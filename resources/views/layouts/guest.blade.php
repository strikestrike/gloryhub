<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> @yield('title') | {{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">

    <style>
        body.login-page {
            background-color: black;
            padding-top: 80px;
            padding-bottom: 60px;
        }

        .logo-img {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto 30px auto;
        }

        .bottom-text {
            text-align: center;
            margin-top: 60px;
        }

        .bottom-text h2 {
            color: white;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .bottom-text h3 {
            color: #eb781f;
            margin: 0;
            font-weight: normal;
        }

        .icheck-primary {
            text-align: left;
        }

        .lang-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="lang-switcher">
        <form method="GET" id="lang-form">
            @csrf
            <select name="locale" onchange="changeLanguage(this.value);" class="form-control">
                @foreach(config('game.languages') as $code => $label)
                <option value="{{ $code }}" {{ app()->getLocale() === $code ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Logo -->
    <img src="/images/logo.png" alt="Logo" class="logo-img">

    <!-- Main content slot -->
    <div class="container d-flex justify-content-center">
        {{ $slot }}
    </div>

    <!-- Bottom Text -->
    <div class="bottom-text">
        <h2>K44</h2>
        <h3>ROYAL PACK DISTRIBUTION</h3>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
    <script>
        function changeLanguage(locale) {
            var form = document.getElementById('lang-form');
            form.action = "{{ url('lang') }}/" + locale;
            form.submit();
        }
    </script>
</body>

</html>