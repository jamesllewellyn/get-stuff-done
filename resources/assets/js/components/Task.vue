<template>
    <div class="task-wrapper">
        <div class="task-background is-fullheight" v-if="isVisible" @click="hideTask"></div>
        <transition name="slide">
            <aside class="task hero is-fullheight" v-if="isVisible">
                <div class="header">
                    <span class="status has-text-left"><i class="fa fa-circle" :class="status" aria-hidden="true"></i></span>
                    <input class="input-title" type="text" name="name" placeholder="Task Name" @change="updateTask" v-model="task.name">
                    <h3> </h3>
                    <p class="is-pulled-left"> created by <strong>You </strong> </p>
                    <p class="has-text-right"> created on <strong v-text="convertDate(task.created_at)"></strong> </p>
                </div>
                <div class="body">
                    <p class="bold">Due Date</p>
                    <p class="control">
                    <!--<p v-text="convertDate(task.due_date)"></p>-->
                    <date-picker :config="{ class: 'input-date', onChange:updateTask }" v-model="task.due_date" @change="updateTask">
                    </date-picker>
                    </p>
                    <div class="columns">
                        <div class=" column">
                            <p class="bold">Priority</p>
                            <span class="select">
                          <select v-model="task.priority_id"  @change="updateTask">
                            <option value="1">High</option>
                            <option value="2">Medium</option>
                            <option  value="3">Low</option>
                          </select>
                        </span>
                        </div>
                        <div class="column">
                            <p class="bold">Status</p>
                            <span class="select">
                          <select v-model="task.status_id"  @change="updateTask">
                            <option value="1">Done</option>
                            <option value="2">Working On It</option>
                          </select>
                        </span>
                        </div>
                    </div>
                    <p class="bold">Note</p>
                    <textarea v-model="task.note"  @change="updateTask"></textarea>
                    <!--<p class="bold">Updates</p>-->
                    <!--<div class="task-updates">-->
                        <!--<textarea name="" id="" cols="30" rows="10"></textarea>-->
                    <!--</div>-->
                    <!--<a class="button is-success is-pulled-right" :class="{'is-loading': isLoading}"  @click="updateTask()">Update</a>-->
                </div>
            </aside>
        </transition>
    </div>
</template>

<script>
    import store from '../store';
    import DatePicker from 'vue-bulma-datepicker'
    export default {
        props: {
            id:{
                default:0
            },
            isLoading:{
                default:false
            },
            isVisible:{
                default:false
            }
        },
        components:{DatePicker},
        computed:{
            task(){
                return store.getters.getTask(this.id);
            },
            status: function(){
                let now = moment();
                /** todo: clean this up **/
                if(this.task.status_id === 1){
                    return  'is-done';
                }
                if( moment(this.task.due_date).isBefore(now) ){
                    return 'is-over-due';
                }
                if(this.task.status_id === 2){
                    return 'is-started';
                }
            }
        },
        methods:{
            convertDate:function(date){
                return moment(date).format("MMM Do YY");
            },
            updateTask:function(){
                this.$store.dispatch('UPDATE_TASK', {id: this.id, task :this.task})
            },
            hideTask:function(){
                this.isVisible = false;
            }
        },
        mounted() {
            let self = this;
            Event.$on('showTask', function(id){
                self.id = id;
                self.isVisible = true;
            });
        }
    }
</script>
