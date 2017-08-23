<template>
    <div id="working-on-it">
        <div class="level header is-mobile">
            <div class="level-left">
                <h1 class="title">Over Due</h1>
            </div>
        </div>
        <hr />
        <div class="columns is-multiline">
            <div class=" column is-half" v-for="section in overDue">
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 v-text="section[0].section.name"></h3>
                        </div>
                        <div class="level-right">
                            <span class="tag is-light is-pulled-right" v-text="section[0].section.project.name"></span>
                        </div>
                    </div>
                    <draggable v-if="section.length != 0" v-model="overDue" @start="drag=true" :options="{handle:'.handle'}"  @end="drag=false"  :element="'table'" class="table task-table" >
                        <transition-group :tag="'tbody'" name="reorder">
                            <task-list v-for="task in section" class="reorder-item"  :sectionId="task.section.id" :task="task"  :key="task.id"></task-list>
                        </transition-group>
                    </draggable>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import store from '../../store';
    import draggable from 'vuedraggable'
    import task from '../../components/Task.vue';
    import taskList from '../../components/TaskList.vue';
    import Modal from '../../components/Modal.vue';
    import Notification from '../../components/Notification.vue';
    export default {
        data() {
            return{

            }
        },
        components: {
            draggable, Notification, Modal, taskList
        },
        computed: {
            overDue:{
                get(){
                    return store.getters.getOverDue;
                },
                set(tasks){

//                    let self = this;
//                    tasks.forEach(function(task){
//                        task.status_id = 2;
//                        self.$store.dispatch('UPDATE_TASK', {id: task.id, task :task})
//                    });
                }
            }
//            overDue:{
//                get(){
//                    return store.getters.getOverDue;
//                },
//                set(tasks){
//                    console.log(tasks);
//                }
//            },
//            upComing:{
//                get(){
//                    return store.getters.getUpComing;
//                },
//                set(tasks){
//                    console.log(tasks);
//                }
//            },
        },
        methods: {
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            }
        },
        mounted() {
            this.$store.dispatch('GET_OVER_DUE');
        }
    }
</script>
