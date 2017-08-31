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
    <link href="//fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar is-hidden-desktop">
            <div class="navbar-brand">
                <div class="navbar-burger is-pulled-left" @click.prevent.stop="triggerEvent('toggleNav')">
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
        <transition name="fade" mode="out-in">
            <section class="main-content columns" v-if="!isLoading" v-cloak>
                <transition name="slide-left">
                    <navigation v-if="navVisible" :add-class="'mobile-side-nav is-hidden-desktop'" :is-mobile-nav="true" v-cloak>
                        <p href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="has-text-centered">
                            Logout
                        </p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    </navigation>
                </transition>

                <navigation :add-class="'is-hidden-touch'" v-cloak>
                    <p href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="has-text-centered">
                        Logout
                    </p>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                </navigation>
                <div class="hero is-fullheight column is-10-desktop is-12-tablet">
                    <transition name="fade" mode="out-in">
                        <router-view :key="$route.params.id"> </router-view>
                    </transition>
                </div>
            </section>
        </transition>
        <transition name="fade" mode="out-in">
            <div class="vue-simple-spinner-wrap hero is-fullheight" v-if="isLoading">
                <vue-simple-spinner size="75" :line-size=6></vue-simple-spinner>
            </div>
        </transition>
        <task></task>
        <profile></profile>
        <are-you-sure></are-you-sure>
        <modal modal-name="addProject" title="Add New Project">
            <template slot="body">
                <add-project></add-project>
            </template>
        </modal>
        <modal modal-name="addTeam" title="Add New Team">
            <template slot="body">
                <add-team></add-team>
            </template>
        </modal>
        <notifications :position="['top', 'right']" animation-type="velocity"   :width=350>
            <template slot="body" scope="props">
                <div class="box notification">
                    <article class="media">
                        <div class="media-left">
                            {{--<img class="circle" src="https://api.adorable.io/avatars/100/jimmyl@laravel-tasks.png" alt="Image">--}}
                            <div class="circle notification-circle" :class="props.item.type">
                            </div>
                        </div>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong v-text="props.item.title"></strong>
                                    <br>
                                    <span v-text="props.item.text">
                                    </span>
                                </p>
                            </div>
                            <nav class="level is-mobile">
                                <div class="level-left">
                                    <a class="level-item">
                                        {{--<span class="icon is-small" @click="view()"><i class="fa fa-reply"></i></span>--}}
                                    </a>
                                    <!--<a class="level-item">-->
                                    <!--<span class="icon is-small" ><i class="fa fa-retweet"></i></span>-->
                                    <!--</a>-->
                                    <a class="level-item">
                                        {{--<span class="icon is-small" @click="markAsRead()"><i class="fa fa-check"></i></span>--}}
                                    </a>
                                </div>
                            </nav>
                        </div>
                    </article>
                </div>



                <div>
                    <a class="title">

                    </a>
                    <a class="close" @click="props.close">
                        <i class="fa fa-fw fa-close"></i>
                    </a>
                    <div v-html="">
                    </div>
                </div>
            </template>
        </notifications>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
