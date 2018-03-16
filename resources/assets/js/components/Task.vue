<template>
    <div class="task-wrapper">
        <!--<transition name="modal" mode="out-in">-->
            <!--<div class="modal-background" v-if="isVisible" @click="hideTask"></div>-->
        <!--</transition>-->
        <transition name="modal" mode="out-in">
            <div class="modal task" v-if="isVisible" :class="{'task-is-loading': taskIsLoading, 'is-active': isVisible}">
                <div class="modal-background" v-if="isVisible" @click="hideTask"></div>
                <div class="modal-card">
                    <div class="task-content" v-if="! taskIsLoading">
                        <header class="task-header">
                            <div class="level">
                                <div class="level-left">
                                    <span class="status has-text-left tooltip is-tooltip-top" :data-tooltip="status.name"><i class="fa fa-circle" :class="status.class" aria-hidden="true"></i></span>
                                    <h2 class="clear-background title h2 full-width"  name="name" placeholder="Task Name" v-text="task.name"></h2>
                                </div>
                                <div class="level-right">
                                    <span class="tag tooltip is-tooltip-left" data-tooltip="Task Priority" :class="priorityDropDownValue.class" v-text="priorityDropDownValue.name"></span>
                                </div>
                            </div>
                            <div class="level">
                                <div class="level-left">
                                    <p> due date <strong v-text="convertDate(task.due_date)"></strong> </p>
                                </div>
                                <div class="level-right">
                                    <p > created on <strong v-text="convertDate(task.created_at)"></strong> </p>
                                </div>
                            </div>
                        </header>
                        <section class="modal-card-body">
                            <div class="level">
                                <div class="level-left">
                                    <!--<p>Assigned members</p>-->
                                    <span class="tooltip is-tooltip-top" :data-tooltip="user.full_name" v-for="user in task.users">
                                    <img class="circle x-small-avatar" :src="user.avatar_url">
                                </span>
                                </div>
                                <div class="level-right">
                                    <button class="button is-info">Edit Task</button>
                                </div>
                            </div>
                            <!--<div class="field">-->
                            <!--<label class="label">Assigned members</label>-->
                            <!--<p class="control">-->
                            <!--<multi-select v-model="task.users" placeholder="Assign to one or more members" label="handle" track-by="full_name" :options="users"  :show-labels="false" :multiple="true" :close-on-select="false" @input="updateTask">-->
                            <!--<template slot="option" slot-scope="props">-->
                            <!--<div class="level">-->
                            <!--<div class="level-item">-->
                            <!--<img class="circle small-avatar" :src="props.option.avatar_url" >-->
                            <!--</div>-->
                            <!--<div class="level-item">-->
                            <!--<span class="has-text-centered">{{ props.option.full_name }}</span>-->
                            <!--</div>-->
                            <!--<div class="level-item"></div>-->
                            <!--<div class="level-item"></div>-->
                            <!--</div>-->
                            <!--</template>-->
                            <!--</multi-select>-->
                            <!--</p>-->
                            <!--</div>-->

                            <!--<div class="columns">-->
                            <!--<div class=" column">-->
                            <!--<div class="field">-->
                            <!--<label class="label">Priority</label>-->
                            <!--<p class="control">-->
                            <!--<multi-select v-model="priorityDropDownValue" :options="[{id:1, name:'High'}, {id:2, name:'Medium'}, {id:3, name:'Low'}]" label="name" :searchable="false" :show-labels="false" placeholder="Set priority"></multi-select>-->
                            <!--</p>-->
                            <!--</div>-->
                            <!--<p class="help is-danger" v-text="getErrors('priority_id')"></p>-->
                            <!--</div>-->
                            <!--<div class="column">-->
                            <!--<div class="field">-->
                            <!--<label class="label">Status</label>-->
                            <!--<p class="control">-->
                            <!--<multi-select v-model="statusDropDownValue" :options="[{id:1, name:'Done'}, {id:2, name:'Working On It'}]" label="name" :searchable="false" :show-labels="false" placeholder="Set current status"></multi-select>-->
                            <!--</p>-->
                            <!--</div>-->
                            <!--</div>-->
                            <!--</div>-->
                            <!--<hr>-->
                            <div class="field">
                                <label class="label">Description</label>
                                <p class="control description">
                                    {{task.note}}
                                </p>
                            </div>
                            <hr>
                            <div class="">
                                <div class="level">
                                    <div class="level-left">
                                        <img class="circle x-small-avatar message-avatar" src="https://api.adorable.io/avatars/100/jimmyl@laravel-tasks.png" >
                                    </div>
                                    <article class="message">
                                        <div class="message-body">
                                            <p class="is-bold">James Llewellyn - Mar 14th 18</p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque risus mi</strong>, tempus quis placerat ut, porta nec nulla. Vestibulum rhoncus ac ex sit amet fringilla. Nullam gravida purus diam, et dictum <a>felis venenatis</a> efficitur. Aenean ac <em>eleifend lacus</em>, in mollis lectus. Donec sodales, arcu et sollicitudin porttitor, tortor urna tempor ligula, id porttitor mi magna a neque. Donec dui urna, vehicula et sem eget, facilisis sodales sem.
                                        </div>
                                    </article>
                                    <!--<div class="message">-->

                                    <!--<p>Eam eu errem probatus necessitatibus, qui comprehensam delicatissimi ad, aliquid dolores accusata qui ea. Eos an laoreet mentitum, mel alii erat ei. Mea erat neglegentur ea, pri ad debet dolor omnes, sea omittam convenire cu. An docendi senserit forensibus mel, duo fastidii salutandi mnesarchum id. Qui purto posse legendos eu, summo denique cu usu, nam ex nonumes maluisset.</p>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        </section>
                        <footer class="modal-card-foot">
                            <button class="button is-success">Save changes</button>
                            <button class="button">Cancel</button>
                        </footer>
                    </div>
                    <transition name="fade" mode="in-out">
                        <div class="vue-simple-spinner-wrap hero is-fullheight" v-if="taskIsLoading">
                            <vue-simple-spinner size="50" :line-size=4 line-fg-color="#2d2b4a"></vue-simple-spinner>
                        </div>
                    </transition>
                </div>
            </div>
        </transition>

            <!--<div class="box task full-height" v-if="isVisible" :class="{'task-is-loading': taskIsLoading}">-->
                <!--<transition name="fade" mode="out-in">-->
                <!--<div class="task-content" v-if="! taskIsLoading">-->
                    <!--<div class="header">-->

                    <!--</div>-->
                    <!--<div class="body">-->

                    <!--</div>-->
                <!--</div>-->
                <!--</transition>-->
                <!--&lt;!&ndash;<div>&ndash;&gt;-->

                <!--&lt;!&ndash;</div>&ndash;&gt;-->
                <!--<footer class="modal-card-foot">-->
                    <!--<button class="button is-success">Save changes</button>-->
                    <!--<button class="button">Cancel</button>-->
                <!--</footer>-->
            <!--</div>-->
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
                taskPriorities:[
                    {'id': 1, 'name':'High', 'class' : 'is-danger'},
                    {'id': 2, 'name':'Medium', 'class' : 'is-warning'},
                    {'id': 3, 'name':'Low', 'class' : 'is-success'}
                ],
                taskStatus:[
                    {id:1, name:'Done', 'class' : 'is-done'},
                    {id:2, name:'Working On It', 'class' : 'is-started'},
                    {id:3, name:'Over Due', 'class' : 'is-over-due'},
                    {id:4, name:'Not Started', 'class' : 'is-light'},
                ],
            }
        },
        components:{DatePicker, MultiSelect},
        computed:{
            task(){
                return store.getters.getTask;
            },
            taskIsLoading: function() {
                return store.state.taskIsLoading;
            },
            status(){
                let now = moment();
                /** todo: clean this up **/
                if(this.task.status_id === 1){
                    return  _.find(this.taskStatus, ['id',1]);
                }
                if( moment(this.task.due_date).isBefore(now) ){
                    return  _.find(this.taskStatus, ['id',4]);
                }
                if(this.task.status_id === 2){
                    return  _.find(this.taskStatus, ['id',2]);
                }
                return  _.find(this.taskStatus, ['id',4]);

            },
            statusDropDownValue:{
                get(){
                    return _.find(this.taskStatus, ['id',this.task.status_id]);
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
                    return _.find(this.taskPriorities, ['id',this.task.priority_id]);
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
                    /** set modal save button to loading status **/
                    this.$store.commit('SET_TASK_IS_LOADING');
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
