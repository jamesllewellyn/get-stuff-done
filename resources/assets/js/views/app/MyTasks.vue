<template>
    <div id="my-tasks">
        <div class="level header is-mobile">
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title">My Tasks</h1>
                </div>
                <div class="level-item">
                    <multi-select v-model="filter" :options="['All', 'Working On It', 'Over Due' ]" :searchable="false" :show-labels="false" placeholder="Filter Tasks"></multi-select>
                </div>
            </div>
        </div>
        <hr />
        <transition  name="fade" mode="out-in" >
            <div class="my-tasks-projects" v-if="!areTasksLoading">
                <div class="columns is-multiline" v-if="tasks">
                    <div class="column is-half" v-for="project in tasks" >
                        <div class="box">
                            <div class="level">
                                <div class="level-left">
                                    <h3 v-text="project[0][0].section.project.team.name"></h3>
                                </div>
                                <div class="level-right">
                                    <span class="tag is-light is-pulled-right" v-text="project[0][0].section.project.name"></span>
                                </div>
                            </div>
                            <section v-for="section in project">
                                <span class="tag is-light" v-text="section[0].section.name"></span>
                                <table class="table task-table">
                                    <tbody>
                                    <task-list v-for="(task, key) in section"
                                               :key="key"
                                               :project_id="task.section.project.id"
                                               :section_id="task.section.id"
                                               :id="task.id"
                                               :name="task.name"
                                               :status_id="task.status_id"
                                               :priority_id="task.priority_id"
                                               :due_date="task.due_date">
                                    </task-list>
                                    </tbody>
                                </table>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import store from '../../store';
    import draggable from 'vuedraggable'
    import task from '../../components/Task.vue';
    import taskList from '../../components/TaskList.vue';
    import Modal from '../../components/Modal.vue';
    import Notification from '../../components/Notification.vue';
    import MultiSelect from 'vue-multiselect';
    export default {
        data() {
            return{
                filter : 'All'
            }
        },
        components: {
            draggable,
            Notification,
            Modal,
            taskList,
            MultiSelect
        },
        computed: {
            areTasksLoading(){
                return store.state.myTasksLoading;
            },
            user: function(){
                /**
                 * user state is blank class if user is not set
                 * check email is set to make sure user data is present
                 * */
                if(!store.state.user.email){
                    /** return false if not user data */
                    return false;
                }
                return store.state.user;
            },
            tasks: function(){
                let tasks = '';
                switch (this.filter){
                    case 'All' :
                        tasks  = store.state.myTasks;
                        break;
                    case 'Working On It' :
                        tasks  = store.state.myWorkingOnIt;
                        break;
                    case 'Over Due':
                        tasks  = store.state.myOverDue;
                        break;
                    default:
                        tasks  = store.state.myTasks;
                }
                return tasks;
            }
        },
        methods: {
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            }
        },
        watch: {
            user () {
                /** wait for user data before fetching users tasks **/
                if(this.user){
                    this.$store.dispatch('GET_MY_TASKS');
                }
            },
            /** filter tasks */
            filter(){
                /** set my tasks page to loading state */
                this.$store.commit('MY_TASKS_LOADING');
                /** show ajax loader */
                switch (this.filter){
                    case 'All' :
                        store.dispatch('GET_MY_TASKS');
                        break;
                    case 'Working On It' :
                        store.dispatch('GET_MY_WORKING_ON_IT');
                        break;
                    case 'Over Due':
                        store.dispatch('GET_MY_OVER_DUE');
                        break;
                    default:
                        store.dispatch('GET_MY_TASKS');
                }
            }
        },
        mounted: function () {
            /** we have user data Call method to get users tasks */
            if(this.user){
                this.$store.dispatch('GET_MY_TASKS');
            }
        }
    }
</script>
