@extends('_layouts.master')

@section('header')

    <script src="{{ mix('js/events.js', 'assets/build') }}"></script>

    <script>
        $(function () {
            $('.more').click(function () {
                    $('.more').toggle()
                }
            );
            $('.less').click(function () {
                    $('.more-content').toggle()
                }
            )
        });
    </script>

@endsection

@section('body')


    <div class="container" id="app">

        @if (!$page->justEvents)
            <h1 class="title">Events Calendar</h1>
        @endif

        <div class="box">
            <div class="content">
                <p>This calendar lists traditional music and dance events in <strong>Michigan</strong>, and sometimes
                    surrounding areas. Unless stated otherwise, all dances welcome new people, with or without a
                    partner, with or without any dance experience. No special dress needed: wear comfortable
                    loose-fitting clothes and bring flat, smooth-soled shoes with clean soles.

                    <a class="more has-text-info">more...</a>
                </p>
                <p class="more hidden">
                    This calendar is a service of <a href="https://aactmad.org">AACTMAD</a>, provided to the many
                    traditional music and dance organizations in and around Michigan. Event information is entered by
                    each individual organization using an account on a separate, free web site. If you would like your
                    events to appear here, please contact the administrators for an account at
                    <a mailto="events@aactmad.org">events@aactmad.org</a>. <a class="has-text-info">less...</a>
                </p>
            </div>
        </div>


        <div id="event-list-container" class="event-list-container">

            <events-listing></events-listing>

        </div>

    </div>



@endsection

