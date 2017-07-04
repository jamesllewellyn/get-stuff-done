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
        <section class="main-content columns is-fullheight">
            <aside class="column is-2 is-narrow-mobile is-fullheight section is-hidden-mobile">
                <div class="side-menu">
                    <div class="has-text-centered">
                        <img src="cowboy.png" alt="">
                    </div>
                    <div class="has-text-centered">
                        <a href="#" class="has-text-centered" role="button" aria-expanded="false">
                            <span v-text="user.name"><span class="caret"></span>
                        </a>
                    </div>
                    <hr/>
                    <p class="menu-label">
                        General
                    </p>
                    <ul class="menu-list">
                        <li>
                            <router-link exact active-class="is-active" tag="a" to="/" >
                                Dashboard
                            </router-link>
                        </li>
                        <li>
                            <router-link exact active-class="is-active" tag="a" to="/profile" >
                                My Profile
                            </router-link>
                        </li>
                    </ul>
                    <hr/>
                    <p class="menu-label">
                        Projects <a  @click.prevent.stop="triggerEvent('toggleModal', 'addProject')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
                    </p>
                    <ul class="menu-list">
                        <li v-for="project in projects">
                            <router-link exact active-class="is-active" tag="a" :to="'/project/'+ project.id" >
                                @{{project.name}}
                            </router-link>
                        </li>
                    </ul>
                </div>
            </aside>
            <div class="container column is-10">
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
