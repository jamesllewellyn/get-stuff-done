<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="//fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">

</head>
<body>
<section class="hero is-small ">
    <div class="hero-head">
        <header class="nav">
            <div class="container">
                <div class="nav-left">
                    <a class="nav-item" href="{{ url('/') }}">
                        <img class="logo is-centered" src="/images/logo.png" alt="">
                    </a>
                </div>
                <span class="nav-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <div class="nav-right nav-menu">
                    <a class="nav-item is-active" href="https://github.com/jamesllewellyn/get-stuff-done">
                            <span class="icon">
                                <i class="fa fa-github"></i>
                            </span>
                    </a>
                    @if (Route::has('login'))
                        @if (Auth::check())
                            <span class="nav-item">
                              <a class="button is-orange" href="{{ url('/') }}">
                                <span>Dashboard</span>
                              </a>
                            </span>
                        @else
                            <a class="nav-item" href="{{ url('/login') }}">
                                Login
                            </a>
                            <span class="nav-item">
                              <a class="button is-orange" href="{{ url('/register') }}">
                                <span>Create Team</span>
                              </a>
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </header>
    </div>
    <div class="hero-body">
        <div class="container">

            @yield('content')

        </div>
    </div>
</section>
@yield('scripts')
</body>
</html>