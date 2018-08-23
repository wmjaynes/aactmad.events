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
    <script
            src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E="
            crossorigin="anonymous">
    </script>
    <script src="/js/navbar.js"></script>

    {{--<link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">--}}

    <link rel="stylesheet" href="https://unpkg.com/bulmaswatch/spacelab/bulmaswatch.min.css">

    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css"/>--}}

    <link rel="stylesheet" href="/css/aactmad.css">

    @yield('header')
</head>
<body>

{{--<div class="container is-fluid">--}}
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
