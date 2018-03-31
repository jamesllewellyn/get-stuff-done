<template>
    <div class="team-dashboard">
        <div class="level header box is-mobile">
            <div class="level-left">
                <drop-down-button :boarder="false" :dropdowns="[{text : 'Delete Team', event: 'team.'+team.id+'.delete', action: 'delete this team', areYouSure : true}]"></drop-down-button>
                <input v-if="team.name != ''" class="title clear-background h1" type="text" name="name" @change="updateTeam" v-model="team.name" v-cloak>
                <h1 v-else class="blokk title" >Project Title</h1>
            </div>
            <logo></logo>
        </div>
        <div class="columns">
            <div class="column is-one-third">
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 class="h3 title">Team Members</h3>
                        </div>
                        <div class="level-right">
                            <a  class="is-pulled-right align-vertical tooltip is-tooltip-left" data-tooltip="Add Team Member" @click.prevent.stop="triggerEvent('toggleModal','addUser')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <template v-for="user in teamMembers">
                            <div class="level">
                                <div class="level-right ">
                                    <img class="circle small-avatar" :src="user.avatar_url" >
                                </div>
                                <div class="level-item has-text-centered">
                                    <span class="is-text-centered">{{ user.full_name }}</span>
                                </div>
                            </div>
                    </template>
                </div>
            </div>
            <div class="column">
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 class="h3 title">Project Overview</h3>
                        </div>
                        <div class="level-right">
                            <a  class="is-pulled-right align-vertical tooltip is-tooltip-left" data-tooltip="Add Project" @click.prevent.stop="triggerEvent('toggleModal','addProject')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <table id="project-overview-table" class="is-fullwidth table has-no-boarders">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="has-text-centered">Not Started</th>
                                <th class="has-text-centered">Working On</th>
                                <th class="has-text-centered">Complete</th>
                                <th class="has-text-centered">Over Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="project in team.projects">
                                <router-link exact active-class="is-active" tag="tr" :to="'/project/'+ project.id" >
                                    <td>{{project.name}}</td>
                                    <td class="has-text-centered">
                                        <span class="tag is-light" v-text="getOverview(project.id).not_started"></span>
                                    </td>
                                    <td class="has-text-centered">
                                        <span class="tag is-yellow" v-text="getOverview(project.id).working_on"></span>
                                    </td>
                                    <td class="has-text-centered">
                                        <span class="tag is-green" v-text="getOverview(project.id).complete"></span>
                                    </td>
                                    <td class="has-text-centered">
                                        <span class="tag is-red" v-text="getOverview(project.id).over_due"></span>
                                    </td>
                                </router-link>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <modal modal-name="addUser" title="Add Team Memeber">
            <template slot="body">
                <add-user></add-user>
            </template>
        </modal>
    </div>
</template>

<script>
    import store from '../../store';
    import dropDownButton from '../../components/DropDownButton.vue';
    import Modal from '../../components/Modal.vue';
    import addUser from '../../components/modals/AddUser.vue';
    import logo from '../../components/logo.vue';
    export default {
        data() {
            return{

            }
        },
        components:{dropDownButton, Modal, addUser, logo},
        computed: {
            team: function(){
                return store.getters.getActiveTeam;
            },
            teamMembers: function(){
                return store.getters.getTeamUser;
            }
        },
        methods: {
            updateTeam:function(){
                this.$store.dispatch('UPDATE_TEAM', {team :this.team})
            },
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            getOverview: function(projectId){
//                if(!this.store.getters.getProjectOverviewById(projectId)){
//                    return
//                }
                return store.getters.getProjectOverviewById(projectId);
            }
        },
        watch: {
            /** whenever Team changes, get team overview */
            team(){
                this.$store.dispatch('GET_TEAM_OVERVIEW');
            }
        },
        created() {
            if(this.team) {
                this.$store.dispatch('GET_TEAM_OVERVIEW');
            }
        },
        beforeRouteUpdate (to, from, next) {
            next();
        },
    }
</script>
