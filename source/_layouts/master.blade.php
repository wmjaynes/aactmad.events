<!DOCTYPE html>
<html>
<head>
    @if($page->production)
        @include('_partials.googleAnalytics')
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AACTMAD</title>

    <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">

    <link rel="stylesheet" href="/css/aactmad.css">

    @yield('header')
</head>
<body>

    @if ($page->justEvents)
        @include('_partials.eventsNavbar')
    @else
        @include('_partials.navbar')
    @endif

    <div>
        @yield('body')
    </div>


    @include('_partials.footer')

{{--</div>--}}
</body>
</html>
