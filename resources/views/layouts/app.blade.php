<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar is-hidden-desktop">
            <div class="navbar-brand">
                <div class="navbar-burger is-pulled-left" @click.prevent.stop="triggerEvent('showNav')">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
                <div class="nav-right">
                    <a class="nav-item">
                        {{--Getting Stuff Done--}}
                    </a>
                </div>
        </nav>
        <section class="main-content columns">

            <transition name="slide-left">
                <navigation v-if="navVisible" :add-class="'mobile-side-nav'" :projects="projects" :user="user" :is-mobile-nav="true"></navigation>
            </transition>

            <navigation :add-class="'is-hidden-touch'" :projects="projects" :user="user"></navigation>

            <div class="container column is-10-desktop is-12-tablet hero">
                <transition name="fade" mode="out-in" v-bind:key="$route.params.id">
                    <router-view ></router-view>
                </transition>
            </div>
            <task></task>
        </section>
        <modal modal-name="addProject" title="Add New Project">
            <template slot="body">
                <add-project></add-project>
            </template>
        </modal>
        <notifications />
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
