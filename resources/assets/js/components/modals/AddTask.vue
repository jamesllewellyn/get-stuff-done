<template>
    <div>
        <section class="modal-card-body">
            <form>
                <div class="field">
                    <label class="label">Task</label>
                    <p class="control">
                        <input class="input" type="text" name="name" placeholder="Task Name" v-model="task.name">
                    </p>
                    <p class="help is-danger" v-text="getErrors('name')"></p>
                </div>
                <div class="field">
                    <label class="label">Due Date</label>
                    <p class="control">
                        <date-picker :config="{ wrap: true }" v-model="task.due_date">
                        </date-picker>
                    </p>
                    <p class="help is-danger" v-text="getErrors('due_date')"></p>
                </div>
                <div class="field">
                    <label class="label">Priority</label>
                    <p class="control">
                        <multi-select v-model="task.priority_id" :options="[{id:1, name:'High'}, {id:2, name:'Medium'}, {id:3, name:'Low'}]" label="name" :searchable="false" :show-labels="false" placeholder="Set priority"></multi-select>
                    </p>
                    <p class="help is-danger" v-text="getErrors('priority_id')"></p>
                </div>
                <div class="field">
                    <label class="label">Assign members</label>
                    <p class="control">
                        <multi-select v-model="task.users" placeholder="Assign to one or more members" label="handle" track-by="full_name" :options="users"  :show-labels="false" :multiple="true" :close-on-select="false">
                            <template slot="option" slot-scope="props">
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
                    <label class="label">Note</label>
                    <p class="control">
                        <textarea class="textarea" placeholder="Enter any task notes here" v-model="task.note"></textarea>
                    </p>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <a class="button is-success" :class="{'is-loading': isLoading}"  @click="addTask()">Save changes</a>
        </footer>
    </div>
</template>

<script>
    import Task from '../../core/Task';
    import store from '../../store';
    import DatePicker from 'vue-bulma-datepicker';
    import MultiSelect from 'vue-multiselect'
    export default {
        data() {
            return{
                task: new Task({id:'', name:'', users: ''}),
                modalName: 'addTask'
            }
        },
        components:{DatePicker, MultiSelect},
        props: {
            sectionId : {
                required: true
            }
        },
        computed:{
            users:function() {
                return store.getters.getTeamUser
            },
            isLoading: function() {
                return store.getters.getModalByName(this.modalName).isLoading;
            },
            fullname:function(user){
                return user.first_name +' '+user.last_name;
            }
        },
        methods: {
            addTask () {
                /** set modal save button to loading status **/
                store.commit('SET_BUTTON_TO_LOADING', {name : this.modalName});
                /** dispatch add new project action **/
                store.dispatch('ADD_NEW_TASK', {sectionId:this.sectionId, task: this.task });
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            }
        },
        mounted() {
        }
    }
</script>
