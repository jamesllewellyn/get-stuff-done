<template>
    <aside class="column is-2 is-fullheight hero" :class="addClass">
            <div class="side-menu">
                <div class="navbar-burger is-pulled-left is-active" @click.prevent.stop="triggerEvent('toggleNav', '')" v-if="isMobileNav">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="is-centered">
                    <figure class="has-text-centered">
                        <img class="logo is-centered" src="/images/logo.png" alt="">
                    </figure>
                </div>
                <div class="has-text-centered">
                    <div class="has-dropdown is-hoverable">
                        <a href="#" class="has-text-centered " role="button" aria-expanded="false">
                            <span v-text="getName()"><span class="caret"></span></span>
                        </a>
                        <div id="userDropdown" class="navbar-dropdown is-hidden-mobile" >
                            <a class="navbar-item" href="#">
                                <div class="navbar-content">
                                    <slot></slot>
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
                        <router-link exact active-class="is-active" tag="a" to="/inbox" >
                            Inbox <span class="tag is-light is-pulled-right" v-text="inboxCount" v-if="inboxCount"></span>
                        </router-link>
                    </li>
                    <li @click.prevent.stop="triggerEvent('toggleNav' , '')">
                        <router-link exact active-class="is-active" tag="a" to="/my-tasks" >
                            My Tasks
                        </router-link>
                    </li>
                    <li>
                        <a @click.prevent.stop="profileHandler">
                            Profile
                        </a>
                    </li>
                </ul>
                <hr/>
                <p class="menu-label">
                    Team <a class="is-pulled-right align-vertical tooltip is-tooltip-right" data-tooltip="Add Team" @click.prevent.stop="triggerEvent('toggleModal', 'addTeam')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
                </p>
                <p class="control multi-select">
                    <multi-select :value.sync="activeTeam" :options="teams" label="name" :searchable="false" :show-labels="false" placeholder="Switch Teams"  @select="switchTeam"></multi-select>
                </p>
                <p class="menu-label">
                    Projects <a class="is-pulled-right align-vertical tooltip is-tooltip-right" data-tooltip="Add Project" @click.prevent.stop="triggerEvent('toggleModal', 'addProject')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
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
    import MultiSelect from 'vue-multiselect';
    import store from '../store';
    import { mapState, mapGetters } from 'vuex'
    export default {
        props:{
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
        components:{MultiSelect},
        computed:{
            profileVisible () { return this.$store.state.profileVisible },
            user () { return  this.$store.state.user },
            teams () { return  this.$store.state.teams },
            activeTeam () { return  this.$store.getters.getActiveTeam },
            projects () { return this.$store.getters.getProjects },
            inboxCount () { return this.$store.state.notifications.length }
        },
        methods:{
            getName:function(){
                return this.user.first_name+ ' ' + this.user.last_name;
            },
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            profileHandler: () =>{
                Event.$emit('toggleProfile');
                Event.$emit('toggleNav');
            },
            switchTeam:function(team){
                this.$store.dispatch('SWITCH_TEAM', {teamId :team.id})
            }
        }
    }
</script>