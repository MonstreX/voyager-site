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

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body>

    @yield('header')

    @yield('page-header')

    @yield('page-content')

    @yield('page-footer')

    <script src="{{ mix('js/vendor.js') }}"></script>

    @stack('javascript')

    <script src="{{ mix('js/app.js') }}"></script>

</body>
</html>