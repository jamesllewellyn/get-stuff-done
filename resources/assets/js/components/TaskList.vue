<template>
    <tr >
        <td>
            <span class="handle is-hidden-mobile" aria-hidden="true">: :</span>
            <a v-if="!placeHolder" class="status" @click.prevent.stop="done()"><i class="fa fa-circle" aria-hidden="true" :class="status"></i> </a>
            <i v-else class="fa fa-circle" aria-hidden="true" :class="status"></i>
        </td>
        <td class="is-centered-text" :class="placeHolder ? 'blokk' : ''">
            <a v-if="!placeHolder" @click.prevent.stop="showTask()">{{name}}</a>
            <span v-else>{{name}}</span>
        </td>
        <td class="is-centered-text" :class="placeHolder ? 'blokk' : ''">{{priority}} </td>
        <td class="is-centered-text" :class="placeHolder ? 'blokk' : ''">{{ dueDateFormatted }}</td>
    </tr>
</template>

<script>
    import store from '../store';
    export default {
        props:{
            project_id:{
                type: Number,
                required: true
            },
            section_id:{
                type: Number,
                required: true
            },
            id:{
                type: Number,
                required: true,
            },
            name:{
                type: String,
                required: true,
            },
            priority_id:{
                required: true,
            },
            due_date:{
                required: true,
            },
            status_id:{
                required: true,
            },
            placeHolder:{
                type: Boolean,
                default: false
            }
        },
        computed:{
            priority : function () {
                switch (this.priority_id){
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
            dueDateFormatted : function () {
                return moment(this.due_date).format("MMM Do YY");
            },
            status: function(){
                let now = moment();
                /** todo: clean this up **/
                if(!this.status_id){
                    return  '';
                }
                if(this.status_id === 1){
                    return  'is-done';
                }
                if(this.status_id === 2){
                    return 'is-started';
                }
                if( moment(this.due_date).isBefore(now) ){
                    return 'is-over-due';
                }
            }
        },
        methods:{
            showTask: function(){
                if(!this.placeHolder){
                    Event.$emit('showTask',this.project_id, this.section_id,this.id);
                }
            },
            done: function () {
                /** flag task as done **/
                this.$store.dispatch('TASK_SET_TO_DONE', {projectId: this.project_id, sectionId: this.section_id, id: this.id});
            },
            forceUpdate(){
                this.$forceUpdate();
            }
        },
        mounted() {
            let self = this;
            /** listen task updated */
            Event.$on('task.'+this.id+'.updated', function() {
                self.forceUpdate();
            });
        }
    }
</script>
