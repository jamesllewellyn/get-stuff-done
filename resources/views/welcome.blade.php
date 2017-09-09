<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="//fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">

</head>
<body>
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
                              <a class="button is-orange" href="{{ url('/create') }}">
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
                <a class="button has-shadow" href="{{ url('/create') }}">
                    {{--<span class="icon">--}}
                    {{--<i class="fa fa-download"></i>--}}
                    {{--</span>--}}
                    <span>
                      Start Getting Stuff Done
                    </span>
                </a>
            </p>
        </div>
    </div>
</section>
<div class="section main">
    <div class="container">
        <div class="columns">
            {{--<div class="column is-4">--}}
                {{--<div class="panel">--}}
                    {{--<div class="panel-block section">--}}
                        {{--<p class="has-text-centered"><i class="fa fa-camera-retro icon-block"></i></p>--}}
                        {{--<br>--}}
                        {{--<p>Open source projects.</p>--}}
                        {{--<br>--}}
                        {{--<p class="has-text-centered"><a class="button is-info is-outlined">Action</a></p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="column is-4">--}}
                {{--<div class="panel">--}}
                    {{--<div class="panel-block section">--}}
                        {{--<p class="has-text-centered"><i class="fa fa-bar-chart icon-block"></i></p>--}}
                        {{--<br>--}}
                        {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean efficitur sit amet massa--}}
                            {{--fringilla egestas. Nullam condimentum luctus turpis.</p>--}}
                        {{--<br>--}}
                        {{--<p class="has-text-centered"><a class="button is-info is-outlined">Action</a></p>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}
            {{--<div class="column is-4">--}}
                {{--<div class="panel">--}}
                    {{--<div class="panel-block section">--}}
                        {{--<p class="has-text-centered"><i class="fa fa-cloud icon-block"></i></p>--}}
                        {{--<br>--}}
                        {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean efficitur sit amet massa--}}
                            {{--fringilla egestas. Nullam condimentum luctus turpis.</p>--}}
                        {{--<br>--}}
                        {{--<p class="has-text-centered"><a class="button is-info is-outlined">Action</a></p>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}
        </div>
    </div>
</div>
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
                <a class="icon" href="https://github.com/jamesllewellyn/get-stuff-done">
                    <i class="fa fa-github"></i>
                </a>
            </p>
        </div>
    </div>
</footer>
</body>
</html>
