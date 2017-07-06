<template>
    <aside class="column is-2 is-fullheight hero" :class="addClass">
        <div class="side-menu">
            <div class="navbar-burger is-pulled-left is-active" @click.prevent.stop="triggerEvent('showNav')" v-if="isMobileNav">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="has-text-centered">
                <img src="cowboy.png" alt="">
            </div>
            <div class="has-text-centered">
                <a href="#" class="has-text-centered" role="button" aria-expanded="false">
                    <span v-text="user.name"><span class="caret"></span></span>
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
                        {{project.name}}
                    </router-link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script>
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
            }
        },
        methods:{
            triggerEvent: function(eventName){
                Event.$emit(eventName);
            }
        },
        mounted() {
//            console.log('Nav mounted.')
        }
    }
</script>