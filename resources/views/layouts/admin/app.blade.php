<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracer Study - STKIP Persada Khatulistiwa</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('/asset/img/icon/stkip.png') }}" type="image/png">

    @stack('header')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts.admin.navbar')
        @include('layouts.admin.sidebar')

        <div class="content-wrapper">
            @yield('content')
            {{ isset($slot) ? $slot : null }}
        </div>

        @include('layouts.admin.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    @stack('footer')
</body>

</html>
