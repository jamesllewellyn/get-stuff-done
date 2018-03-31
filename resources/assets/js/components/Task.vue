<template>
    <div class="task-wrapper">
        <transition name="modal" mode="out-in">
            <div class="modal task" v-if="isVisible"
                 :class="{'task-is-loading': taskIsLoading, 'is-active': isVisible}">
                <div class="modal-background" v-if="isVisible" @click="hideTask"></div>
                <div class="modal-card">
                    <!--<div class="task-content" v-if="! taskIsLoading">-->
                    <header class="task-header">
                        <div class="level">
                            <div class="level-left">
                                    <span class="status has-text-left tooltip is-tooltip-top"
                                          :data-tooltip="status.name">
                                        <i class="fa fa-circle" :class="status.class" aria-hidden="true"></i>
                                    </span>
                                <h2 class="clear-background modal-card-title full-width" name="name"
                                    placeholder="Task Name" v-text="task.name">
                                </h2>
                            </div>
                            <div class="level-right">
                                <span class="tag tooltip is-tooltip-left" data-tooltip="Task Priority"
                                      :class="priorityDropDownValue.class" v-text="priorityDropDownValue.name">
                                </span>
                            </div>
                        </div>
                        <div class="level">
                            <div class="level-left">
                                <p> due date <strong v-text="convertDate(task.due_date)"></strong></p>
                            </div>
                            <div class="level-right">
                                <p > created on <strong v-text="convertDate(task.created_at)"></strong></p>
                            </div>
                        </div>
                    </header>
                    <section class="modal-card-body">
                        <div class="level">
                            <div class="level-left">
                                <span class="tooltip is-tooltip-right" :data-tooltip="user.full_name"
                                      v-for="user in task.users">
                                    <img class="circle x-small-avatar" :src="user.avatar_url">
                                </span>
                            </div>
                            <div class="level-right">
                                <button class="button is-info">
                                    <span  @click="editTask">Edit Task</span>
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Description</label>
                            <p class="control description">
                                {{task.note}}
                            </p>
                        </div>
                        <hr>
                        <div class="task-comment" v-for="comment in task.comments">
                            <div class="level">
                                <div class="level-left">
                                    <img class="circle x-small-avatar message-avatar" :src="comment.author.avatar_url">
                                </div>
                                <article class="message">
                                    <div class="message-body">
                                        <p class="is-bold"><span v-text="comment.author.full_name"></span> - <span
                                                v-text="convertDate(comment.created_at)"></span></p>
                                        {{comment.comment}}
                                    </div>
                                </article>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <textarea class="task-textarea"
                                  :class="{'is-danger': getErrors('comment')}"
                                  v-model="comment" placeholder="Add comment to task">
                        </textarea>
                        <button class="button is-success" @click="addComment">Add Comment</button>
                    </footer>
                    <!--</div>-->
                    <!--<transition name="fade" mode="in-out">-->
                    <!--<div class="vue-simple-spinner-wrap hero is-fullheight" v-if="taskIsLoading">-->
                    <!--<vue-simple-spinner size="50" :line-size=4 line-fg-color="#2d2b4a"></vue-simple-spinner>-->
                    <!--</div>-->
                    <!--</transition>-->
                </div>
            </div>
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
            return {
                isVisible: false,
                projectId: false,
                sectionId: false,
                comment: null,
                editingTask: false,
                id: false,
                taskPriorities: [
                    {'id': 1, 'name': 'High', 'class': 'is-danger'},
                    {'id': 2, 'name': 'Medium', 'class': 'is-warning'},
                    {'id': 3, 'name': 'Low', 'class': 'is-success'}
                ],
                taskStatus: [
                    {id: 1, name: 'Done', 'class': 'is-done'},
                    {id: 2, name: 'Working On It', 'class': 'is-started'},
                    {id: 3, name: 'Over Due', 'class': 'is-over-due'},
                    {id: 4, name: 'Not Started', 'class': 'is-light'},
                ],
            }
        },
        components: {DatePicker, MultiSelect},
        computed: {
            task() {
                return store.getters.getTask;
            },
            taskIsLoading: function () {
                return store.state.taskIsLoading;
            },
            status() {
                let now = moment();
                /** todo: clean this up **/
                if (this.task.status_id === 1) {
                    return _.find(this.taskStatus, ['id', 1]);
                }
                if (moment(this.task.due_date).isBefore(now)) {
                    return _.find(this.taskStatus, ['id', 4]);
                }
                if (this.task.status_id === 2) {
                    return _.find(this.taskStatus, ['id', 2]);
                }
                return _.find(this.taskStatus, ['id', 4]);

            },
            statusDropDownValue: {
                get() {
                    return _.find(this.taskStatus, ['id', this.task.status_id]);
                },
                set(value) {
                    if (typeof value === 'object') {
                        /** update task status id */
                        this.task.status_id = value.id;
                        /** called update method */
                        this.updateTask();
                        return false;
                    }
                    this.task.status_id = null;
                }
            },
            priorityDropDownValue: {
                get() {
                    return _.find(this.taskPriorities, ['id', this.task.priority_id]);
                },
                set(value) {
                    if (typeof value === 'object') {
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
        methods: {
            convertDate(date) {
                return moment(date).format("MMM Do YY");
            },
            addComment() {
                this.$store.dispatch('ADD_COMMENT', {
                    projectId: this.projectId,
                    sectionId: this.sectionId,
                    id: this.task.id,
                    comment: this.comment
                })
            },
            commentAdded() {
                this.comment = null;
                Event.$emit('notify', 'success', 'Success', 'Comment added to task');
                let container = this.$el.querySelector(".modal-card-body");
                container.scrollTop = container.scrollHeight;
            },
            editTask(){
                this.isVisible = false;
                Event.$emit('toggleModal', 'editTask');
            },
            hideTask() {
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
            id() {
                /** dispatch action */
                if (this.id) {
                    /** set modal save button to loading status **/
                    this.$store.commit('SET_TASK_IS_LOADING');
                    this.$store.dispatch('GET_TASK', {
                        projectId: this.projectId,
                        sectionId: this.sectionId,
                        id: this.id
                    });
                }
            },
        },
        mounted() {
            let self = this;
            Event.$on('showTask', function (projectId, sectionId, id) {
                self.projectId = projectId;
                self.sectionId = sectionId;
                self.id = id;
                self.isVisible = true;
            });
            Event.$on('comment.success', function () {
                self.commentAdded();
            });
        }
    }
</script>
