<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <style>
            td {
                font-size: 14px !important;
            }

            .no-underline {
                text-decoration: none !important; 
            }
    </style>
    <link rel="stylesheet" type="text/css" href="../../css/app.css"/>
    @stack('styles')
</head>
<body>
<div id="app" class="flex flex-row" style="width: 100%; height: 100%;">
    @guest
        <div class="flex-grow-1 d-flex flex-column overflow-hidden">
            <div class="d-flex">
                <div class="flex-grow-1 overflow-auto py-4 mx-5">
                    @yield('content')
                </div>
            </div>
        </div>
    @else
        <div class="flex-grow-1 d-flex flex-column overflow-hidden">

            <div class="d-flex row">
                <div class="text-white bg-dark offcanvas-md offcanvas-start" style="width:260px" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                    @include('layouts.sidebar')
                </div>
                <div class="flex-grow-1 overflow-auto py-4 mx-1 mx-md-5">
                    <div class="d-flex flex-row-reverse  d-block d-md-none">
                        <div class="col-1 mx-4">
                            <a class="btn btn-dark" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                   <path d="M4 20h16"></path>
                                   <path d="M4 12h16"></path>
                                   <path d="M4 4h16"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    @endguest
    @stack('scripts')
    @include('config.app')
</div>
</body>
</html>
