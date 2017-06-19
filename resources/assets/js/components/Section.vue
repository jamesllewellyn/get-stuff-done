<template>
 <div class=" column is-half">
     <div class="box">
         <h3 class="has-text-centered">{{section.name}}<a  @click.prevent.stop="toggelModal('addTask')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a></h3>
         <table class="table task-table">
             <draggable v-model="section.tasks" @start="drag=true" :options="{handle:'.handle'}" :change="onMove" @end="drag=false"  :element="'tbody'">
                 <task v-for="task in section.tasks" :sectionId="section.id" :projectId="projectId" :taskId="task.id"  :key="task.id"></task>
             </draggable>
         </table>
     </div>
 </div>
</template>

<script>
    import draggable from 'vuedraggable'
    import Task from './Task.vue';
    import appStore from '../app-store';
    export default {
        props: {
            section: {
                required: true
            },
            projectId: ''
        },
        components: {
          Task , draggable
        },
        computed:{

        },
        methods:{
            toggelModal: function(modalName){
                Event.$emit(modalName);
                Event.$emit('clickedSection', this.section.id);
            },
            onMove:  function(movedTask){
                console.log(movedTask);
            }
       },
        mounted() {
        }
    }
</script>
