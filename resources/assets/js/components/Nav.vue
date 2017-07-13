<template>
    <aside class="column is-2 is-fullheight hero" :class="addClass">
            <div class="side-menu">
                <div class="navbar-burger is-pulled-left is-active" @click.prevent.stop="triggerEvent('toggleNav', '')" v-if="isMobileNav">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="has-text-centered">
                    <img src="cowboy.png" alt="">
                </div>
                <div class="has-text-centered">
                    <div class="has-dropdown is-hoverable">
                        <a href="#" class="has-text-centered " role="button" aria-expanded="false">
                            <span v-text="user.name"><span class="caret"></span></span>
                        </a>
                        <div id="userDropdown" class="navbar-dropdown is-hidden-mobile" >
                            <a class="navbar-item" href="#">
                                <div class="navbar-content">
                                    <p class="has-text-centered">
                                        Switch Account
                                    </p>
                                </div>
                            </a>
                            <a class="navbar-item" href="#">
                                <div class="navbar-content">
                                    <p class="has-text-centered">
                                        Logout
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
                <hr/>
                <p class="menu-label">
                    General
                </p>
                <ul class="menu-list">
                    <li @click.prevent.stop="triggerEvent('toggleNav' , '')">
                        <router-link exact active-class="is-active" tag="a" to="/" >
                            Dashboard
                        </router-link>
                    </li>
                    <li @click.prevent.stop="profileHandler">
                        <a href="#" :class="{'is-active' : profileVisible}">
                            My Profile
                        </a>
                    </li>
                </ul>
                <hr/>
                <p class="menu-label">
                    Projects <a  @click.prevent.stop="triggerEvent('toggleModal', 'addProject')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
                </p>
                <ul class="menu-list">
                    <li v-for="project in projects" @click.prevent.stop="triggerEvent('toggleNav', '')">
                        <router-link exact active-class="is-active" tag="a" :to="'/project/'+ project.id" >
                            {{project.name}}
                        </router-link>
                    </li>
                </ul>
            </div>
        </aside>
</template>

<script>
    import store from '../store';
    import { mapState, mapGetters } from 'vuex'
    export default {
        props:{
            projects:{
                required:true
            },
            user:{
                required:true
            },
            addClass:{
                required:false
            },
            isMobileNav:{
                required:false,
                default: false
            },
            showDropdown:{
                required:false,
                default: false
            }
        },
        computed:
            mapState([
                 'profileVisible'
            ])
        ,
        methods:{
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            profileHandler: () =>{
                Event.$emit('toggleProfile');
                Event.$emit('toggleNav');
            }
        },
        mounted() {
//            console.log('Nav mounted.')
        }
    }
</script>