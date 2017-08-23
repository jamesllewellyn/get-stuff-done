<template>
    <div class="task-wrapper">
        <!--<div class="task-background modal-background is-fullheight" ></div>-->
        <transition name="modal" mode="out-in">
            <div class="modal-background" v-if="isVisible" @click="hideTask"></div>
        </transition>
        <transition name="slide">
            <aside class="task hero is-fullheight has-shadow" v-if="isVisible">
                <div class="header">
                    <span class="status has-text-left"><i class="fa fa-circle" :class="status" aria-hidden="true"></i></span>
                    <input class="clear-background title h2" type="text" name="name" placeholder="Task Name" @change="updateTask" v-model="task.name">
                    <h3> </h3>
                    <p class="is-pulled-left"> created by <strong>You </strong> </p>
                    <p class="has-text-right"> created on <strong v-text="convertDate(task.created_at)"></strong> </p>
                </div>
                <div class="body">
                    <div class="field">
                        <label class="label">Assign members</label>
                        <p class="control">
                            <multi-select v-model="task.users" placeholder="Assign to one or more members" label="handle" track-by="full_name" :options="users"  :show-labels="false" :multiple="true" :close-on-select="false" @input="updateTask">
                                <template slot="option" scope="props">
                                    <div class="level">
                                        <div class="level-item">
                                            <img class="circle small-avatar" :src="props.option.avatar_url" >
                                        </div>
                                        <div class="level-item">
                                            <span class="has-text-centered">{{ props.option.full_name }}</span>
                                        </div>
                                        <div class="level-item"></div>
                                        <div class="level-item"></div>
                                    </div>

                                </template>
                            </multi-select>
                        </p>
                    </div>
                    <div class="field">
                        <label class="label">Due Date</label>
                        <p class="control">
                            <date-picker :config="{ class: 'input-date', onChange:updateTask }" v-model="task.due_date" @change="updateTask"></date-picker>
                        </p>
                    </div>

                    <div class="columns">
                        <div class=" column">
                            <div class="field">
                                <label class="label">Priority</label>
                                <p class="control">
                                    <multi-select v-model="task.priority_id" :options="[{id:1, name:'High'}, {id:2, name:'Medium'}, {id:3, name:'Low'}]" label="name" :searchable="false" :show-labels="false" placeholder="Set priority"  @select="updateTask"></multi-select>
                                </p>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Status</label>
                                <p class="control">
                                    <multi-select v-model="task.status_id" :options="[{id:1, name:'Done'}, {id:2, name:'Working On It'}]" label="name" :searchable="false" :show-labels="false" placeholder="Set current status"  @select="updateTask"></multi-select>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Note</label>
                        <p class="control">
                            <textarea v-model="task.note" class="textarea" @change="updateTask"></textarea>
                        </p>
                    </div>
                </div>
            </aside>
        </transition>
    </div>
</template>

<script>
    import Task from '../core/Task';
    import store from '../store';
    import DatePicker from 'vue-bulma-datepicker';
    import MultiSelect from 'vue-multiselect';
    export default {
        data() {
            return{
                isVisible: false,
                projectId:false,
                sectionId:false,
                id:false,
                isLoading: false
            }
        },
        components:{DatePicker, MultiSelect},
        computed:{
            task(){
                return store.getters.getTask;
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
            },
            users:function() {
                return store.getters.getTeamUser
            }
        },
        methods:{
            convertDate:function(date){
                return moment(date).format("MMM Do YY");
            },
            updateTask:function(){
                this.$store.dispatch('UPDATE_TASK', {projectId: this.projectId, sectionId:this.sectionId, id: this.id, task :this.task})
            },
            hideTask:function(){
                this.isVisible = false;
                this.$store.commit('CLEAR_TASK');
            }
        },
        watch: {
            /** whenever id changes, get new task data */
            id () {
                /** dispatch action */
                if (this.id) {
                    this.$store.dispatch('GET_TASK', {projectId: this.projectId, sectionId : this.sectionId, id: this.id});
                }
            },
        },
        mounted() {
            let self = this;
            Event.$on('showTask', function(projectId, sectionId, id){
                self.projectId = projectId;
                self.sectionId = sectionId;
                self.id = id;
                self.isVisible = true;
            });
        }
    }
</script>
