<template>
 <div class=" column is-half">
     <div class="box" :class="placeHolder ? 'place-holder' : ''">
         <div class="level">
             <div class="level-left">
                 <input class="clear-background title h3" type="text" name="name" placeholder="Section Name" @change="updateSection" v-model="section.name" v-if="section">
                 <h3 v-else class="title blokk"> Section Name</h3>
             </div>
             <div class="level-right">
                 <a  @click.prevent.stop="addTask()" v-if="!placeHolder"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
                 <i v-else class="fa fa-circle is-pulled-right align-vertical" aria-hidden="true"></i>
             </div>
         </div>
         <draggable v-if="tasks" v-model="tasks" @start="drag=true" :options="{handle:'.handle'}"  @end="drag=false"  :element="'table'" class="table task-table" >
             <transition-group :tag="'tbody'" name="reorder">
                <task-list v-for="task in tasks" class="reorder-item" :projectId="projectId" :sectionId="section.id" :task="task"  :key="task.id"></task-list>
             </transition-group>
         </draggable>
         <notification v-else-if="!placeHolder" :status="'info'">This section currently has no tasks</notification>
         <table class="table place-holder" v-else>
             <tbody>
                <task-list   v-for="n in 3" :key="n" :placeHolder="true" ></task-list>
             </tbody>
         </table>
     </div>
 </div>
</template>

<script>
    import draggable from 'vuedraggable'
    import TaskList from './TaskList.vue';
    import Notification from './Notification.vue';
    import store from '../store';
    export default {
        props: {
            projectId:{
                type: Number,
                required: false
            },
            id:{
                type: Number,
                required: false
            },
            placeHolder:{
                type: Boolean,
                default: false
            }
        },
        components: {
            TaskList , draggable , Notification
        },
        computed:{
            section: function() {
                /** get  project via route param */
                if(this.projectId) {
                    return store.getters.getSectionById({projectId: this.projectId, sectionId: this.id});
                }
            },
            tasks: {
                get() {
                    if(this.id) {
                        return store.getters.getSectionById({sectionId: this.id}).tasks;
                    }
                },
                set(tasks) {
                    let sort_order = 1;
                    tasks.forEach(function(task){
                        task.sort_order = sort_order;
                        sort_order ++;
                    });
                    this.$store.dispatch('UPDATE_SECTION_TASKS_SORT_ORDER', {section : this.section, tasks :tasks})
                }
            }
        },
        methods:{
            /** trigger event */
            addTask: function(){
                Event.$emit('toggleModal','addTask');
                Event.$emit('clickedSection',this.id);
            },
            updateSection:function(){
                this.$store.dispatch('UPDATE_SECTION', {id: this.id, section :this.section})
            },
        },
        mounted() {
        }
    }
</script>
