<template>
    <tr>
        <td>
            <span class="handle" aria-hidden="true">: :</span>
            <a class="status" @click.prevent.stop="done()"><i class="fa fa-circle" aria-hidden="true" :class="status"></i> </a>
        </td>
        <td class="is-centered-text"><a @click.prevent.stop="showTask()">{{task.name}}</a></td>
        <td class="is-centered-text">{{priority}} </td>
        <td class="is-centered-text">{{ due_date }}</td>
    </tr>
</template>

<script>
    import store from '../store';
    export default {
        props:{
            id:{
                required: true
            }
        },
        computed:{
            task: function(){
                return store.getters.getTask(this.id);
            },
            priority : function () {
                switch (this.task.priority_id){
                    case 1 :
                    case "1" :
                        return 'High';
                        break;
                    case 2 :
                    case "2" :
                        return 'Medium';
                    case 3:
                    case "3" :
                        return 'Low';
                }
            },
            due_date : function () {
                return moment(this.task.due_date).format("MMM Do YY");
            },
            status: function(){
                let now = moment();
                if(this.task.status_id == 1){
                    return  'is-done';
                }
                if( moment(this.task.due_date).isBefore(now) ){
                    return 'is-over-due';
                }
            }
        },
        methods:{
            toggelModal: function(modalName){
                Event.$emit(modalName);
                Event.$emit('clickedTask', this.task, this.sectionId);
            },
            showTask: function(){
                Event.$emit('showTask',this.id);
            },
//            done: function () {
//                let self = this;
//                axios.put('/api/task/' + self.task.id + '/done' )
//                    .then(function (response) {
//                        /** update task in array */
//                        self.task.isDone();
//                        /** toggle addTask modal */
//                        Event.$emit('swalSuccess', 'Task Done, Yaaaay');
//                    })
//                    .catch(function (error) {
//                        /** if error keep modal open and display errors */
//                        if(error.response.data){
//                            self.errors.record(error.response.data);
//                            Event.$emit('updateTaskToggleLoading');
//                        }
//                    });
//            }
        },
        mounted() {

        }
    }
</script>
