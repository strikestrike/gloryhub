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
            background-image: url('/images/background.jpg');
            /* adjust path as needed */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="text-right p-3">
        <form method="GET" id="lang-form">
            @csrf
            <select name="locale" onchange="changeLanguage(this.value);" class="form-control w-auto d-inline-block">
                @foreach(config('game.languages') as $code => $label)
                <option value="{{ $code }}" {{ app()->getLocale() === $code ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </form>
    </div>
    {{ $slot }}
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
    <script>
        function changeLanguage(locale) {
            // Dynamically change the action of the form
            var form = document.getElementById('lang-form');
            form.action = "{{ url('lang') }}/" + locale;

            // Submit the form
            form.submit();
        }
    </script>

</body>

</html>