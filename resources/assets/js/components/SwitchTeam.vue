<template>
    <div class="task-wrapper">
        <transition name="modal" mode="out-in">
            <div class="modal" :class="switchTeamVisible ? 'is-active' : '' " v-if="switchTeamVisible">
                <div class="modal-background" @click.prevent.stop="triggerEvent('toggleSwitchTeam')"></div>
                <div class="modal-content modal-card">
                    <div class="box">
                        <a class="panel-block is-active" v-for="team in teams" @click="switchTeam(team.id)">
                            <!--<span class="panel-icon">-->
                              <!--<i class="fa fa-book"></i>-->
                            <!--</span>-->
                            {{team.name}}
                        </a>
                        <!--<ul>-->
                            <!--<li  v-text="team.name" id="team.id"></li>-->
                        <!--</ul>-->
                    </div>
                </div>
                <button class="modal-close is-large" @click.prevent.stop="triggerEvent('toggleSwitchTeam')"></button>
            </div>
        </transition>
    </div>
</template>

<script>
    import store from '../store';
    import { mapState, mapGetters } from 'vuex';
    export default {
        computed:
            mapState([
                'switchTeamVisible', 'teams'
            ]),
//        mapGetters([
//                     'profileVisible', 'user'
//         ]),

        methods:{
            switchTeam:function(id){
                this.$store.dispatch('SWITCH_TEAM', {teamId :id})
            },
            triggerEvent: function(eventName, payLoad){
                Event.$emit(eventName, payLoad);
            }
        },
    }
</script>
