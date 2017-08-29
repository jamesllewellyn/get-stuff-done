<template>
 <div class=" column is-half">
     <div class="box" :class="placeHolder ? 'place-holder' : ''">
         <div class="level">
             <div class="level-left">
                 <drop-down-button :boarder="false" :dropdowns="[{text : 'Delete Section', event: 'section.'+id+'.delete', action: 'delete this section', areYouSure : true}]" v-if="!placeHolder">
                 </drop-down-button>
                 <input class="clear-background title h3" type="text" name="name" placeholder="Section Name" @change="updateSection" v-model="name" v-if="name">
                 <h3 v-else class="title blokk"> Section Name</h3>
             </div>
             <div class="level-right">
                 <a  @click.prevent.stop="addTask()" v-if="!placeHolder"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a>
                 <i v-else class="fa fa-circle is-pulled-right align-vertical" aria-hidden="true"></i>
             </div>
         </div>
         <table class="table place-holder" v-if="tasks.length > 0">
             <tbody>
                <task-list v-for="(task, key) in tasks"
                           :key="key"
                           :project_id="projectId"
                           :section_id="id"
                           :id="task.id"
                           :name="task.name"
                           :status_id="task.status_id"
                           :priority_id="task.priority_id"
                           :due_date="task.due_date"

                ></task-list>
             </tbody>
         </table>
         <!--<draggable v-if="tasks.length > 0" v-model="tasks" @start="drag=true" :options="{handle:'.handle'}"  @end="drag=false"  :element="'table'" class="table task-table" >-->
             <!--<transition-group :tag="'tbody'" name="reorder">-->
                <!--<task-list v-for="task in tasks" class="reorder-item" :projectId="projectId" :sectionId="section.id" :id="task.id"  :key="task.id"></task-list>-->
             <!--</transition-group>-->
         <!--</draggable>-->
         <notification v-else-if="!placeHolder" :status="'info'">This section currently has no tasks</notification>
         <table class="table place-holder" v-else>
             <tbody>
                <task-list v-for="n in 3" :key="n" :placeHolder="true" ></task-list>
             </tbody>
         </table>
     </div>
 </div>
</template>

<script>
//    import draggable from 'vuedraggable'
    import TaskList from './TaskList.vue';
    import Notification from './Notification.vue';
    import dropDownButton from './DropDownButton.vue';
    import store from '../store';
    export default {
        data: function () {
            return { name: this.sectionName }
        },
        props: {
            projectId:{
                type: Number,
                required: false
            },
            id:{
                type: Number,
                required: false
            },
            sectionName:{
                type: String,
                required: false
            },
            placeHolder:{
                type: Boolean,
                default: false
            }
        },
        components: {
            TaskList , Notification, dropDownButton
        },
        computed:{
            tasks: {
                get() {
                    if(!this.id) {
                        return [];
                    }
                    if(!store.getters.getSectionById({sectionId: this.id}).tasks){
                        return [];
                    }
                    return store.getters.getSectionById({sectionId: this.id}).tasks;

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
                this.$store.dispatch('UPDATE_SECTION', {id: this.id, section :{id: this.id, name: this.name }})
            },
            deleteSection(){
                this.$store.dispatch('DELETE_SECTION', {id: this.id})
            },
            /**
             * use force update to re-render instance
             * when section task object had been updated
             * **/
            forceUpdate(){
                this.$forceUpdate();
            }
        },
        mounted() {
            let self = this;
            /** listen section delete event */
            Event.$on('section.'+this.id+'.delete', function() {
                self.deleteSection();
            });
            /** listen section updated */
            Event.$on('section.'+this.id+'.updated', function() {
                self.forceUpdate();
            });
        }
    }
</script>
