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
                        Getting Stuff Done
                    </a>
                </div>
        </nav>
        <transition name="fade" mode="out-in">
            <section class="main-content columns" v-if="!isLoading" v-cloak>

                {{--Nav bar--}}
                <navigation :add-class="'is-hidden-touch'" v-cloak></navigation>

                {{--Mobile nav bar --}}
                <transition name="slide-left">
                    <navigation v-if="navVisible" :add-class="'mobile-side-nav is-hidden-desktop'" :is-mobile-nav="true" v-cloak></navigation>
                </transition>

                <div class="hero is-fullheight column is-10-desktop is-12-tablet">
                    <transition name="fade" mode="out-in">
                        <router-view :key="$route.params.id"> </router-view>
                    </transition>
                </div>

            </section>
        </transition>
        <transition name="fade" mode="out-in">
            <div class="vue-simple-spinner-wrap hero is-fullheight" v-if="isLoading">
                <vue-simple-spinner size="75" :line-size=6 line-fg-color="#2d2b4a"></vue-simple-spinner>
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
        <edit-task></edit-task>
        <notifications :position="['top', 'right']" animation-type="velocity"   :width=350>
            <template slot="body" scope="props">
                <div class="notification box">
                    <article class="media">
                        <div class="media-left">
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
                        </div>
                    </article>
                </div>
            </template>
        </notifications>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
