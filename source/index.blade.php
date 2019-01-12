@extends('_layouts.master')

@section('header')

    <script src="{{ mix('js/events.js', 'assets/build') }}"></script>

    <script>
        function detectIE() {
            var ua = window.navigator.userAgent;

            var msie = ua.indexOf('MSIE ');
            if (msie > 0) {
                // IE 10 or older => return version number
                return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
            }

            var trident = ua.indexOf('Trident/');
            if (trident > 0) {
                // IE 11 => return version number
                var rv = ua.indexOf('rv:');
                return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
            }

            // other browser
            return false;
        }

    </script>

    <script>
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        };

        $(function () {
            $('.more').click(function () {
                    $('.more').toggle()
                }
            );
            $('.less').click(function () {
                    $('.more-content').toggle()
                }
            );

            if (getUrlParameter('noControls')) {
                $('.controls').hide();
            }
        });
    </script>

@endsection

@section('body')


    <div class="container">

        @if (!$page->justEvents)
            <h1 class="title">Events Calendar</h1>
        @endif

        <div class="box controls">

            <div class="content">
                <p>This calendar lists traditional music and dance events in <strong>Michigan</strong>, and sometimes
                    surrounding areas. Unless stated otherwise, all dances welcome new people, with or without a
                    partner, with or without any dance experience. No special dress needed: wear comfortable
                    loose-fitting clothes and bring flat, smooth-soled shoes with clean soles.

                    <a class="more" title="More information">
                        <span class="icon">
                        <i class="fas fa-caret-down"></i>
                        </span></a>
                </p>
                <div class="more hidden">
                    <p>
                        This calendar is a service of <a href="https://aactmad.org">AACTMAD</a>, provided to the many
                        traditional music and dance organizations in and around Michigan. Event information is entered
                        by each individual organization using an account on a separate, free web site. If you would like
                        your events to appear here, please contact the administrators for an account at
                        <a href="mailto:events@aactmad.org">events@aactmad.org</a>.

                        <a title="Less information">
                        <span class="icon">
                        <i class="fas fa-caret-up"></i>
                        </span></a>
                    </p></div>
            </div>
        </div>


        <div id="app" class="event-list-container">
            <coming-events-container></coming-events-container>

            <backtotop></backtotop>
            <events-listing></events-listing>

        </div>

        <script>
            var version = detectIE();
            if (version === false) {
            }
            else {
                document.getElementById("ie-warning").style.display = "block";
            }
        </script>

    </div>



@endsection

