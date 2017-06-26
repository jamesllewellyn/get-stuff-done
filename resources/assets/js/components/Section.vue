<template>
 <div class=" column is-half">
     <div class="box">
         <h3 class="has-text-centered">{{section.name}}<a  @click.prevent.stop="addTask()"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a></h3>
         <table class="table task-table">
             <draggable v-model="tasks" @start="drag=true" :options="{handle:'.handle'}"  @end="drag=false"  :element="'tbody'">
                 <task v-for="task in tasks" :sectionId="section.id" :projectId="projectId" :id="task.id"  :key="task.id"></task>
             </draggable>
         </table>
     </div>
 </div>
</template>

<script>
    import draggable from 'vuedraggable'
    import Task from './Task.vue';
    import store from '../store';
    export default {
        props: {
            id:'' ,
            projectId: '',
        },
        components: {
          Task , draggable
        },
        computed:{
            section: function() {
                /** get  project via route param */
                return store.getters.getSectionById({ projectId :this.projectId, sectionId :this.id  });
            },
            tasks: {
                get() {
                     return store.getters.getSectionById({ projectId :this.projectId, sectionId :this.id  }).tasks;
                },
                set(tasks) {
                    let sort_order = 1;
                    tasks.forEach(function(task){
                        task.sort_order = sort_order;
                        sort_order ++;
                    });
                    this.$store.dispatch('UPDATE_SECTION_TASKS_SORT_ORDER', {projectId: this.projectId, section : this.section, tasks :tasks})
                }
            }

        },
        methods:{
            /** trigger event */
            addTask: function(){
                Event.$emit('toggleModal','addTask');
                Event.$emit('clickedSection',this.id);
            }
        },
        mounted() {
        }
    }
</script>
