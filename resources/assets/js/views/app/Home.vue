<template>
    <div class="container">
        <h1 class="title">
            Dashboard
        </h1>
        <div class="has-text-right">
            <span class="tag is-orange is-medium">
                <a  @click.prevent.stop="triggerEvent('toggleModal', 'addProject')" class="orange">Add Project</a>
            </span>
        </div>
        <!--<div class="tabs is-centered">-->
            <!--<ul>-->
                <!--<li class="is-active"><a>List</a></li>-->
                <!--<li><a>Calender</a></li>-->
                <!--<li><a>Chats</a></li>-->
            <!--</ul>-->
        <!--</div>-->
        <hr />
        <div>
            <div class="columns is-multiline">
                <div class=" column is-full">
                    <div class="box">
                        <h3 class="has-text-centered">Working On It</h3>
                        <table class="table task-table">
                            <draggable v-model="workingOnIt" @start="drag=true" :options="{handle:'.handle', group:'tasks'}"  @end="drag=false" :element="'table'">
                                <transition-group :tag="'tbody'" name="reorder">
                                    <dashboardTask v-for="task in workingOnIt"  :id="task.id" :task="task" :key="task.id"></dashboardTask>
                                </transition-group>
                            </draggable>
                        </table>
                    </div>
                </div>
                <div class=" column is-half">
                    <div class="box">
                        <h3 class="has-text-centered">Over Due</h3>
                        <table class="table task-table">
                            <draggable v-model="overDue" @start="drag=true" :options="{handle:'.handle', group:'tasks'}"  @end="drag=false"  :element="'table'">
                                <transition-group :tag="'tbody'" name="reorder">
                                    <dashboardTask v-for="task in overDue"  :task="task" :id="task.id" :key="task.id"></dashboardTask>
                                </transition-group>
                            </draggable>
                        </table>
                    </div>
                </div>
                <div class=" column is-half">
                    <div class="box">
                        <h3 class="has-text-centered">Deadlines Coming</h3>
                        <table class="table task-table">
                            <draggable v-model="upComing" @start="drag=true" :options="{handle:'.handle', group:'tasks'}"  @end="drag=false"  :element="'table'">
                                <transition-group :tag="'tbody'" name="reorder">
                                    <dashboardTask v-for="task in upComing"  :task="task" :id="task.id"   :key="task.id"></dashboardTask>
                                </transition-group>
                            </draggable>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import store from '../../store';
    import draggable from 'vuedraggable'
    import dashboardTask from '../../components/DashboardTask.vue';
    export default {
        data() {
            return{

            }
        },
        components: {
            dashboardTask, draggable
        },
        computed: {
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
            }
        },
        methods: {
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            }
        },
        mounted() {
        }
    }
</script>
