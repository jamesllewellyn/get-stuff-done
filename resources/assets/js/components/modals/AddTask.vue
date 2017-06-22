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
                        <span class="select">
                          <select v-model="task.priority_id">
                            <option value="1">High</option>
                            <option value="2">Medium</option>
                            <option  value="3">Low</option>
                            </select>
                        </span>
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
    import DatePicker from 'vue-bulma-datepicker'
    export default {
        data() {
            return{
                task: new Task({id:'', name:'', tasks: [], created_at: ''}),
                modalName: 'addTask',
            }
        },
        components:{DatePicker},
        props: {
            projectId : {
                required: true
            },
            sectionId : {
                required: true
            }
        },
        computed:{
            isLoading: function() {
                return store.getters.getModalByName(this.modalName).isLoading;
            }
        },
        methods: {
            addTask () {
                /** set modal save button to loading status **/
                store.commit('SET_BUTTON_TO_LOADING', {name : this.modalName});
                /** dispatch add new project action **/
                store.dispatch('ADD_NEW_TASK', {projectId: this.projectId, sectionId:this.sectionId, task: this.task });
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
