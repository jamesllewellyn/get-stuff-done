<template>
    <div class="task-wrapper">
        <transition name="modal" mode="out-in">
            <div class="modal-background" v-if="isVisible" @click="hideTask"></div>
        </transition>
        <transition name="slide">
            <aside class="task hero is-fullheight has-shadow" v-if="isVisible">
                <div class="header">
                    <span class="status has-text-left"><i class="fa fa-circle" :class="statusClass" aria-hidden="true"></i></span>
                    <input class="clear-background title h2" type="text" name="name" placeholder="Task Name" @change="updateTask" v-model="task.name">
                    <p class="help is-danger" v-text="getErrors('name')"></p>
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
                        <p class="help is-danger" v-text="getErrors('due_date')"></p>
                    </div>

                    <div class="columns">
                        <div class=" column">
                            <div class="field">
                                <label class="label">Priority</label>
                                <p class="control">
                                    <multi-select v-model="priorityDropDownValue" :options="[{id:1, name:'High'}, {id:2, name:'Medium'}, {id:3, name:'Low'}]" label="name" :searchable="false" :show-labels="false" placeholder="Set priority"></multi-select>
                                </p>
                            </div>
                            <p class="help is-danger" v-text="getErrors('priority_id')"></p>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Status</label>
                                <p class="control">
                                    <multi-select v-model="statusDropDownValue" :options="[{id:1, name:'Done'}, {id:2, name:'Working On It'}]" label="name" :searchable="false" :show-labels="false" placeholder="Set current status"></multi-select>
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
            statusClass(){
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
            statusDropDownValue:{
                get(){
                    switch (this.task.status_id) {
                        case 1 :
                        case "1" :
                            return {id:1, name:'Done'};
                        case 2 :
                        case "2" :
                            return {id:2, name:'Working On It'};
                    }
                },
                set(value){
                    if(typeof value === 'object'){
                        /** update task status id */
                        this.task.status_id = value.id;
                        /** called update method */
                        this.updateTask();
                        return false;
                    }
                    this.task.status_id = null;
                }
            },
            priorityDropDownValue:{
                get(){
                    switch (this.task.priority_id){
                        case 1 :
                        case "1" :
                            return {id:1, name:'High'};
                        case 2 :
                        case "2" :
                            return {id:2, name:'Medium'};
                        case 3:
                        case "3" :
                            return {id:3, name:'Low'};
                    }
                },
                set(value){
                    if(typeof value === 'object'){
                        /** update task priority_id */
                        this.task.priority_id = value.id;
                        /** called update method */
                        this.updateTask();
                        return false;
                    }
                    this.task.priority_id = null;
                }
            },
            users() {
                return store.getters.getTeamUser
            }
        },
        methods:{
            convertDate:function(date){
                return moment(date).format("MMM Do YY");
            },
            updateTask:function(){
                this.$store.dispatch('UPDATE_TASK', {
                    projectId: this.projectId,
                    sectionId:this.sectionId,
                    id: this.task.id,
                    task : {
                        id :this.task.id,
                        name: this.task.name,
                        due_date: this.task.due_date,
                        note: this.task.note,
                        priority_id : this.task.priority_id,
                        sort_order : this.task.sort_order,
                        status_id : this.task.status_id,
                        users : this.task.users
                    }
                })
            },
            hideTask:function(){
                this.isVisible = false;
                this.id = false;
                this.$store.commit('CLEAR_TASK');
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
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
