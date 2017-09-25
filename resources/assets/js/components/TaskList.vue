<template>
    <tr >
        <td>
            <!--<span class="handle is-hidden-mobile" aria-hidden="true">: :</span>-->
            <a v-if="!placeHolder" class="status" data-tooltip="Complete Task" @click.prevent.stop="done()" :class="status_id != 1 ? 'tooltip is-tooltip-left' : ''">
                <i class="fa fa-circle" aria-hidden="true" :class="status">
                </i>
            </a>
            <i v-else class="fa fa-circle" aria-hidden="true" :class="status"></i>
        </td>
        <td class="is-centered-text tooltip is-tooltip-left" data-tooltip="View Task" :class="placeHolder ? 'blokk' : ''">
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
                required: false
            },
            section_id:{
                type: Number,
                required: false
            },
            id:{
                type: Number,
                required: false,
            },
            name:{
                type: String,
                required: false,
                default : 'Task Name'
            },
            priority_id:{
                required: false,
                default : 1
            },
            due_date:{
                required: false,
                default : moment().format("MMM Do YY")
            },
            status_id:{
                required: false,
                default: 1
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
                /** if task is already completed display alert*/
                if(this.status_id === 1){
                    Event.$emit('notify','information', 'Information', 'Task is already flagged as compelted');
                    /** end process */
                    return false;
                }
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
