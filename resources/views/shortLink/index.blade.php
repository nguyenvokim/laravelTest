<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
    <meta name="description" content="">
    <meta name="author" content="Jeremy Kenedy">
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Fonts --}}
    @yield('template_linked_fonts')

    {{-- Styles --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    @yield('template_linked_css')

    <style type="text/css">
        @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
            background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;
            background-size: auto 100%;
        }
        @endif

    </style>

    {{-- Scripts --}}
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>

</head>
<body>
<div id="app">
    <div class="container">

        @include('partials.form-status')

    </div>

    <div class="container">
        <h1 class="text-right">
            REDIRECT IN <span id="countdown"></span>s
        </h1>
        <div>
            Some ADS goes here, Some ADS goes here,Some ADS goes here,<br/>
            Some ADS goes here, Some ADS goes here,Some ADS goes here,<br/>
            Some ADS goes here, Some ADS goes here,Some ADS goes here,<br/>
            Some ADS goes here, Some ADS goes here,Some ADS goes here,<br/>
            
        </div>
    </div>

</div>

{{-- Scripts --}}
<script src="{{ mix('/js/app.js') }}"></script>
<script type="text/javascript">
    var timeRedirect = 11;
    $(document).ready(function () {
        setInterval(function () {
            timeRedirect = timeRedirect - 1;
            if (timeRedirect < 0) {
                timeRedirect = 0
            }
            $('#countdown').html(timeRedirect);
            if (timeRedirect == 0) {
                window.location = '{{$shortLink->link_redirect}}';
            }
        }, 1000)
    })
</script>
</body>
</html>
