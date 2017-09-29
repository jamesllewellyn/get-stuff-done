<!DOCTYPE html>
<html class="is-landing-page">
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
<div id="app">
    <section class="hero is-medium has-text-centered">
        <div class="hero-head">
            <header class="nav">
                <div class="container">
                    <div class="nav-left">
                        <a class="nav-item" href="{{ url('/home') }}">
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
                                  <a class="button is-orange" href="{{ url('/home') }}">
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
            <div class="container has-text-centered">
                <h1 class="title is-2">
                    Get Stuff Done
                </h1>
                <h2 class="subtitle is-5">
                    Team based project to-do list application.
                </h2>
                <p>
                    <a class="button has-shadow" href="{{ url('/register') }}">
                        <span>
                          Start Getting Stuff Done
                        </span>
                    </a>
                </p>
            </div>
            <carousel-3d :width=720 :height=390>
                <slide :index="0">
                    <img src="/images/home-slider/team_dashboard.png">
                </slide>
                <slide :index="1">
                    <img src="/images/home-slider/create_task.png">
                </slide>
                <slide :index="2">
                    <img src="/images/home-slider/inbox.png">
                </slide>
            </carousel-3d>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-4 has-text-centered" >
                    <span class="icon is-large">
                        <i class="fa fa-sitemap"></i>
                    </span>
                    <p class="title has-text-centered">Organisation</p>
                    <p class="subtitle has-text-centered">Quickly and easily keep track of the stuff you need to do and get it done</p>
                </div>
                <div class="column is-4 has-text-centered" >
                    <span class="icon is-large">
                        <i class="fa fa-user"></i>
                    </span>
                    <p class="title has-text-centered">Collaborate</p>
                    <p class="subtitle has-text-centered">Add friend and colleagues, assign each other task and get your stuff done </p>
                </div>
                <div class="column is-4 has-text-centered" >
                    <span class="icon is-large">
                        <i class="fa fa-github"></i>
                    </span>
                    <p class="title has-text-centered">Free</p>
                    <p class="subtitle has-text-centered">Open source project on <a href="https://github.com/jamesllewellyn/get-stuff-done">Git Hub</a>. Pull request are always welcome</p>
                </div>

            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="container">
            <div class="content has-text-centered">
                <p>
                    <strong>Get Stuff Done</strong> by <a href="https://github.com/jamesllewellyn/">James Llewellyn</a>.
                    {{--The source code is licensed--}}
                    {{--<a href="http://opensource.org/licenses/mit-license.php">MIT</a>. The website content--}}
                    {{--is licensed <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/">CC ANS 4.0</a>.--}}
                </p>
                <p>
                    <strong>App Avatars</strong> by <a href="http://avatars.adorable.io/">Adorable Avatars</a>.
                </p>
                <p>
                    <a class="icon" href="https://github.com/jamesllewellyn/get-stuff-done">
                        <i class="fa fa-github"></i>
                    </a>
                </p>
            </div>
        </div>
    </footer>
</div>
<!-- Scripts -->
<script src="{{ asset('js/welcome.js') }}"></script>
</body>
</html>
