<template>
 <div class=" column is-half">
     <div class="box">
         <h3 class="has-text-centered">{{name}}<a  @click.prevent.stop="toggelModal('addTask')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a></h3>
         <table class="table task-table">
             <tbody>
             <task v-for="task in tasks" :name="task.name"  :key="task.id" :id="task.id" :priority_id="task.priority_id" :due_date="task.due_date"  ></task>
             </tbody>
         </table>
     </div>
 </div>
</template>

<script>
    import Task from './Task.vue';
    export default {
        props: {
            name: {
                type: String,
                required: true
            },
            tasks:{
                type: Array,
                required: true
            } ,
            id: {
                type: Number,
                required: true
            }
        },
        components: {
          Task
        },
        methods:{
            toggelModal: function(modalName){
                Event.$emit(modalName);
                Event.$emit('clickedSection', this.id);
            }
       },
        mounted() {
        }
    }
</script>
