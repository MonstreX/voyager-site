<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="description" content="{{ $seo['keywords'] }}">

    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">

    <link href="{{ asset('vendor/voyager-site/css/app.css') }}" rel="stylesheet">

    {!! scripts('head') !!}

</head>
<body>

    {!! scripts('body_start') !!}

    @yield('header')

    @yield('page-header')

    @yield('page-content')

    @yield('page-footer')

    @stack('javascript')

    <script type="module" src="{{ asset('vendor/voyager-site/js/app.js') }}"></script>

    {!! scripts('body_end') !!}

</body>
</html>
