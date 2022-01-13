<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="/alerts/css/iao-alert.min.css">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        // prettier-ignore
        [
            {supported: 'Symbol' in window, fill: 'https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser-polyfill.min.js'},
            {supported: 'Promise' in window, fill: 'https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js'},
            {supported: 'fetch' in window, fill: 'https://cdn.jsdelivr.net/npm/fetch-polyfill@0.8.2/fetch.min.js'},
            {supported: 'CustomEvent' in window && 'log10' in Math && 'sign' in Math &&  'assign' in Object &&  'from' in Array &&
                    ['find', 'findIndex', 'some', 'includes'].reduce(function(previous, prop) { return (prop in Array.prototype) ? previous : false; }, true), fill: 'https://unpkg.com/filepond-polyfill/dist/filepond-polyfill.js'}
        ].forEach(function(p) {
            if (p.supported) return;
            document.write('<script src="' + p.fill + '"><\/script>');
        });
    </script>
</head>
<body>
    <div id="app" class="mb-5">
        <nav class="navbar fixed-top navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('notes.index') }}">{{ __('My Notes') }}</a>
                                    <a class="dropdown-item" href="{{ route('notes.create') }}">{{ __('Add Note') }}</a>
                                    <a class="dropdown-item" href="{{ route('notes.share-with-me') }}">{{ __('Shared With Me') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <main class="@if(Route::currentRouteName() != 'home') py-5 @endif">
            @yield('content')
        </main>
    </div>
    {{--
    <div class="footer-bottom-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    © 2021 <strong>ShareNote</strong>. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>--}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('alerts/js/iao-alert.jquery.min.js') }}"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    @yield('scripts')

    <!-- Show success message -->
    @if (session('status'))
        <script>
            var message = '{{session('status')}}';
            $.iaoAlert({ msg: message, type: "success", mode: "dark", alertTime: "10000" });
        </script>
    @endif
    <script>
        function search(){
            let q = document.getElementById('intro-keywords').value ;
            if(q.length > 2) location.href = '/?q='+q;
        }
    </script>
</body>
</html>
