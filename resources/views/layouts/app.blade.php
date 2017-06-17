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
        <div class="columns layout-cols">
            <aside class="column is-2 is-hidden-mobile hero menu is-fullheight">
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
                        Projects <a  @click.prevent.stop="toggelModal('addProject')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
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
            <div class="column content">
                <nav class="nav" id="top">
                    <div class="container">
                        <div class="nav-left">
                            <a class="nav-item" href="{{ url('/') }}">
                                <!-- Branding Image -->
                                {{ config('app.name', 'Laravel') }}
                            </a>
                        </div>
                        <span class="nav-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                        </span>
                        <div class="nav-right nav-menu is-hidden-tablet">
                            <a class="nav-item is-tab is-active">
                                Menu 1
                            </a>
                            <a class="nav-item is-tab">
                                Menu 2
                            </a>
                            <a class="nav-item is-tab">
                                Menu 3
                            </a>
                            <a class="nav-item is-tab">
                                Menu 4
                            </a>
                        </div>
                        <!-- Authentication Links -->
                        <div class="nav-right">
                            <a class="nav-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>

                    </div>
                </nav>
                <transition name="fade" mode="out-in">
                    <router-view ></router-view>
                </transition>
            </div>
        </div>
        <modal modal-name="addProject" title="Add New Project">
            <div slot="body">
                <form>
                    <div class="field">
                        <label class="label">Project Name</label>
                        <p class="control">
                            <input class="input" type="text" name="name" placeholder="Project Name" v-model="newProject.name">
                        </p>
                        <p class="help is-danger" v-text="errors.get('name')"></p>
                    </div>
                    {{--<div class="field">--}}
                        {{--<label class="label">Due Date</label>--}}
                        {{--<p class="control">--}}
                            {{--<datepicker :config="{ wrap: true }" v-model="newTask.due_date">--}}
                            {{--</datepicker>--}}
                        {{--</p>--}}
                        {{--<p class="help is-danger" v-text="errors.get('name')"></p>--}}
                    {{--</div>--}}
                </form>
            </div>
        </modal>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
