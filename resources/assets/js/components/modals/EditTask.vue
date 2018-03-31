<template>
    <transition name="modal" mode="out-in">
        <div class="modal" :class="modal.isVisible ? 'is-active' : '' " v-if="modal.isVisible">
            <div class="modal-background"  @click="hideModal()"></div>
            <div class="modal-card modal-container">
                <header class="modal-card-head">
                    <p class="modal-card-title">Edit Task</p>
                    <button class="delete" @click="hideModal()"></button>
                </header>
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
                            <label class="label">Status</label>
                            <p class="control">
                                <multi-select v-model="status"
                                              :options="[{id:1, name:'Done'}, {id:2, name:'Working On It'}]"
                                              label="name" :searchable="false" :show-labels="false"
                                              placeholder="Set current status">
                                </multi-select>
                            </p>
                        </div>
                        <div class="field">
                            <label class="label">Priority</label>
                            <p class="control">
                                <multi-select v-model="priority" :options="[{id:1, name:'High'}, {id:2, name:'Medium'}, {id:3, name:'Low'}]" track-by="id" label="name" :searchable="false" :show-labels="false" placeholder="Set priority"></multi-select>
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
                            <label class="label">Description</label>
                            <p class="control">
                                <textarea class="textarea" placeholder="Enter any task description here" v-model="task.note"></textarea>
                            </p>
                        </div>
                    </form>
                </section>
                <footer class="modal-card-foot">
                    <a class="button is-success" :class="{'is-loading': isLoading}"  @click="updateTask()">Save changes</a>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>
    import Task from '../../core/Task';
    import store from '../../store';
    import DatePicker from 'vue-bulma-datepicker';
    import MultiSelect from 'vue-multiselect'
    export default {
        data() {
            return{
                modalName: 'editTask'
            }
        },
        components:{DatePicker, MultiSelect},
//        props: {
//            sectionId : {
//                required: true
//            }
//        },
        computed:{
            modal: function(){
                return store.getters.getModalByName(this.modalName);
            },
            task() {
                return store.getters.getTask;
            },
            users:function() {
                return store.getters.getTeamUser
            },
            isLoading: function() {
                return store.getters.getModalByName(this.modalName).isLoading;
            },
            priority: {
                get() {
                    return this.task.getPriority();
                },
                set(value) {
                    if (typeof value !== 'object') {
                        return this.task.setPriority(null);
                    }
                    /** update task status id */
                    return this.task.setPriority(value.id);
                }
            },
            status: {
                get() {
                    return this.task.getStatus();
                },
                set(value) {
                    if (typeof value !== 'object') {
                        this.task.setStatus(null);
                    }
                    /** update task status id */
                    return this.task.setStatus(value.id);
                }
            },
        },
        methods: {
            updateTask() {
                this.$store.dispatch('UPDATE_TASK', {
                    sectionId: this.task.section.id,
                    projectId: this.task.project.id,
                    id: this.task.id,
                    task: {
                        id: this.task.id,
                        name: this.task.name,
                        due_date: this.task.due_date,
                        note: this.task.note,
                        priority_id: this.task.priority_id,
                        sort_order: this.task.sort_order,
                        status_id: this.task.status_id,
                        users: this.task.users
                    }
                })
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            },
            hideModal: function(){
                store.commit('TOGGLE_MODAL_IS_VISIBLE', {name : this.modalName});
                /** go back to task **/
                Event.$emit('showTask', this.task.project.id, this.task.section.id, this.task.id);
            }
        },
        mounted() {
            store.commit('ADD_MODAL',  { name:  this.modalName })
        }
    }
</script>
