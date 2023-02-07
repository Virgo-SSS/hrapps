<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('layouts.styles')
    @yield('styles')
</head>
<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>

    @if(!Auth::check())
        <div class="login-area login-s2">
            <div class="container">
                <div class="login-box ptb--100">
                    @yield('content')
                </div>
            </div>
        </div>
    @endif

    @if(Auth::check())
        <div class="page-container">
            @include('layouts.sidebar')
            <div class="main-content" style="height: auto">
                @include('layouts.header')
                <div class="main-content-inner">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
        @include('layouts.settings')
    @endif

    @include('layouts.scripts')
    @yield('scripts')

    @yield('modal')
</body>
</html>
