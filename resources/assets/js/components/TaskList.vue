<template>
    <tr :class="task.done ? 'strikeout' : ''">
        <td>
            <span class="handle is-hidden-mobile" aria-hidden="true">: :</span>
            <a v-if="!placeHolder" class="status" @click.prevent.stop="done()"><i class="fa fa-circle" aria-hidden="true" :class="status"></i> </a>
            <i v-else class="fa fa-circle" aria-hidden="true" :class="status"></i>
        </td>
        <td class="is-centered-text" :class="placeHolder ? 'blokk' : ''">
            <a v-if="!placeHolder" @click.prevent.stop="showTask()">{{task.name}}</a>
            <span v-else>{{task.name}}</span>
        </td>
        <td class="is-centered-text" :class="placeHolder ? 'blokk' : ''">{{priority}} </td>
        <td class="is-centered-text" :class="placeHolder ? 'blokk' : ''">{{ due_date }}</td>
    </tr>
</template>

<script>
    import store from '../store';
    export default {
        props:{
            projectId:{
                type: Number,
                required: false
            },
            sectionId:{
                type: Number,
                required: false
            },
            task:{
                type: Object,
                required: false,
                default: function () { return {name: 'example task name', priority: 'high', due_date: moment(), status_id : null} }
            },
            placeHolder:{
                type: Boolean,
                default: false
            }
        },
        computed:{
//            task: function(){
//                return store.getters.getTask(this.id);
//            },
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
                /** todo: clean this up **/
                if(this.task.status_id === 1){
                    return  'is-done';
                }

                if(this.task.status_id === 2){
                    return 'is-started';
                }

                if( moment(this.task.due_date).isBefore(now) ){
                    return 'is-over-due';
                }
            }
        },
        methods:{
            showTask: function(){
                if(!this.placeHolder){
                    Event.$emit('showTask',this.projectId, this.sectionId,this.task.id);
                }
            },
            done: function () {
                /** todo: move this into store **/
            }
        },
        mounted() {
//            console.log(this.id);
//            console.log(this.sectionId);
//            console.log(this.task);
        }
    }
</script>
