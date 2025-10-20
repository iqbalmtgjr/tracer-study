<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hendi Sarapta Saragih</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/') }}plugins/fontawesome-free/css/all.min.css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-responsive/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
    <!-- Favicon standar -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/') }}favicon.ico">

    <!-- Alternatif format PNG -->
    <link rel="icon" type="image/png" href="{{ asset('/') }}favicon.png">

    <!-- Favicon untuk perangkat Apple -->
    <link rel="apple-touch-icon" href="{{ asset('/') }}apple-touch-icon.png">

    <!-- Favicon untuk ukuran berbeda -->
    <link rel="icon" sizes="32x32" href="{{ asset('/') }}favicon-32x32.png" type="image/png">
    <link rel="icon" sizes="16x16" href="{{ asset('/') }}favicon-16x16.png" type="image/png">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/') }}dist/css/adminlte.min.css" />
    @stack('header')
</head>
