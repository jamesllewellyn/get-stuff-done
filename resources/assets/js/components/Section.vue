<template>
 <div class=" column is-half">
     <div class="box">
         <h3 class="has-text-centered">{{section.name}}<a  @click.prevent.stop="addTask()"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a></h3>
         <table class="table task-table">
             <draggable v-model="section.tasks" @start="drag=true" :options="{handle:'.handle'}" :change="onMove" @end="drag=false"  :element="'tbody'">
                 <task v-for="task in section.tasks" :sectionId="section.id" :projectId="projectId" :id="task.id"  :key="task.id"></task>
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

        },
        methods:{
            /** trigger event */
            addTask: function(){
                Event.$emit('toggleModal','addTask');
                Event.$emit('clickedSection',this.id);
            },
            onMove:  function(movedTask){
                console.log(movedTask);
            }
        },
        mounted() {
        }
    }
</script>
