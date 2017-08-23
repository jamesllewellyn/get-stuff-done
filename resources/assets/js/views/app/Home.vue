<template>
    <div class="home">
        <div class="level header is-mobile">
            <div class="level-left">
                <input class="title clear-background h1" type="text" name="name" @change="updateTeam" v-model="team.name" v-if="team">
                <!--<h1 class="title" v-text="" ></h1>-->
            </div>
            <div class="level-right">
                <div class="has-text-right">
            <span class="tag is-orange is-medium">
                <a  @click.prevent.stop="triggerEvent('toggleModal', 'addUser')" class="orange">Add Team Member</a>
            </span>
                </div>
            </div>
        </div>
        <hr />
        <div>
            <div class="columns is-multiline">
                <div class="column is-full">
                    <div class="box">
                        <h3 class="has-text-centered">Working On It</h3>
                        <draggable v-if="workingOnIt.length != 0" v-model="workingOnIt" @start="drag=true" :options="{handle:'.handle', group:'tasks'}"  @end="drag=false" :element="'table'" class="table task-table">
                            <transition-group :tag="'tbody'" name="reorder">
                                <dashboardTask v-for="task in workingOnIt"  :id="task.id" :task="task" :key="task.id"></dashboardTask>
                            </transition-group>
                        </draggable>
                        <notification v-else :status="'warning'">You currently aren't working on anything</notification>

                    </div>
                </div>
                <div class=" column is-half">
                    <div class="box">
                        <h3 class="has-text-centered">Over Due</h3>
                        <draggable v-if="overDue.length != 0" v-model="overDue" @start="drag=true" :options="{handle:'.handle', group:'tasks'}"  @end="drag=false"  :element="'table'" class="table task-table">
                            <transition-group :tag="'tbody'" name="reorder">
                                <dashboardTask v-for="task in overDue"  :task="task" :id="task.id" :key="task.id"></dashboardTask>
                            </transition-group>
                        </draggable>
                        <notification v-else :status="'success'">You current have no pending over due tasks</notification>
                    </div>
                </div>
                <div class=" column is-half">
                    <div class="box">
                        <h3 class="has-text-centered">Deadlines Coming</h3>
                        <draggable v-if="upComing.length != 0" v-model="upComing" @start="drag=true" :options="{handle:'.handle', group:'tasks'}"  @end="drag=false"  :element="'table'" class="table task-table">
                            <transition-group :tag="'tbody'" name="reorder">
                                <dashboardTask v-for="task in upComing"  :task="task" :id="task.id"   :key="task.id"></dashboardTask>
                            </transition-group>
                        </draggable>
                        <notification v-else :status="'success'">All upcoming tasks are being worked on</notification>
                    </div>
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
    import draggable from 'vuedraggable'
    import dashboardTask from '../../components/DashboardTask.vue';
    import Modal from '../../components/Modal.vue';
    import Notification from '../../components/Notification.vue';
    import addUser from '../../components/modals/AddUser.vue';
    export default {
        data() {
            return{

            }
        },
        components: {
            dashboardTask, draggable, Notification, Modal, addUser
        },
        computed: {
            team : function () {
                return store.getters.getActiveTeam;
            },
            workingOnIt:{
                get(){
                    return store.getters.getWorkingOnIt;
                },
                set(tasks){
                    let self = this;
                    tasks.forEach(function(task){
                        task.status_id = 2;
                        self.$store.dispatch('UPDATE_TASK', {id: task.id, task :task})
                    });
                }
            },
            overDue:{
                get(){
                    return store.getters.getOverDue;
                },
                set(tasks){
                    console.log(tasks);
                }
            },
            upComing:{
                get(){
                    return store.getters.getUpComing;
                },
                set(tasks){
                    console.log(tasks);
                }
            },
        },
        methods: {
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            updateTeam:function(){
                this.$store.dispatch('UPDATE_TEAM', {team:this.team})
            },
        },
        mounted() {
        }
    }
</script>
