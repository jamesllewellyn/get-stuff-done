<template>
    <div class="container">
            <h1 class="title">
                {{project.name}}
            </h1>
        <div class="has-text-right">
            <span class="tag is-orange is-medium">
                <a  @click.prevent.stop="event('addSection')" class="orange">Add Section</a>
            </span>
        </div>
        <hr>
        <div>
            <div class="columns is-multiline ">
                <projectSection v-for="section in project.sections" :section="section" :key="section.id" :projectId="id"></projectSection>
            </div>
        </div>
        <Modal modalName="addTask" title="Add New Task">
            <div slot="body">
                <form>
                    <div class="field">
                        <label class="label">Task</label>
                        <p class="control">
                            <input class="input" type="text" name="name" placeholder="Task Name" v-model="newTask.name">
                        </p>
                        <p class="help is-danger" v-text="errors.get('name')"></p>
                    </div>
                    <div class="field">
                        <label class="label">Due Date</label>
                        <p class="control">
                            <datepicker :config="{ wrap: true }" v-model="newTask.due_date">
                            </datepicker>
                        </p>
                        <p class="help is-danger" v-text="errors.get('name')"></p>
                    </div>
                    <div class="field">
                        <label class="label">Priority</label>
                        <p class="control">
                            <span class="select">
                              <select v-model="newTask.priority_id">
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
                            <textarea class="textarea" placeholder="Enter any task notes here" v-model="newTask.note"></textarea>
                        </p>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal modalName="addSection" title="Add New Section">
            <div slot="body">
                <form>
                    <div class="field">
                        <label class="label">Section</label>
                        <p class="control">
                            <input class="input" type="text" name="name" placeholder="Section Name" v-model="newSection.name">
                        </p>
                        <p class="help is-danger" v-text="errors.get('name')"></p>
                    </div>
                    <!--<div class="field">-->
                        <!--<label class="label">Due Date</label>-->
                        <!--<p class="control">-->
                            <!--<datepicker :config="{ wrap: true }" v-model="newTask.due_date">-->
                            <!--</datepicker>-->
                        <!--</p>-->
                        <!--<p class="help is-danger" v-text="errors.get('name')"></p>-->
                    <!--</div>-->
                </form>
            </div>
        </Modal>
        <Modal modalName="updateTask" title="Task">
            <div slot="body">
                <form>
                    <div class="field">
                        <label class="label">Task</label>
                        <p class="control">
                            <input class="input" type="text" name="name" placeholder="Task Name" v-model="editTask.name">
                        </p>
                        <p class="help is-danger" v-text="errors.get('name')"></p>
                    </div>
                    <div class="field">
                        <label class="label">Due Date</label>
                        <p class="control">
                            <datepicker :config="{ wrap: true }" v-model="editTask.due_date">
                            </datepicker>
                        </p>
                        <p class="help is-danger" v-text="errors.get('due_date')"></p>
                    </div>
                    <div class="columns">
                        <div class="field column">
                            <label class="label">Priority</label>
                            <p class="control">
                            <span class="select">
                              <select v-model="editTask.priority_id">
                                <option value="1">High</option>
                                <option value="2">Medium</option>
                                <option  value="3">Low</option>
                              </select>
                            </span>
                            </p>
                        </div>
                        <div class="field column">
                            <label class="label">Status</label>
                            <p class="control">
                            <span class="select">
                              <select v-model="editTask.status_id">
                                <option value="1">Done</option>
                                <option value="2">Working On It</option>
                              </select>
                            </span>
                            </p>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Note</label>
                        <p class="control">
                            <textarea class="textarea" placeholder="Enter any task notes here" v-model="editTask.note"></textarea>
                        </p>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<script>
    import appstore from '../../app-store';
    import projectSection from '../../components/Section';
    import Errors from '../../core/Errors';
    import Modal from '../../components/Modal.vue';
    import Section from '../../core/Section';
    import Task from '../../core/Task';
    import Datepicker from 'vue-bulma-datepicker'
    export default {
        data() {
            return{
                /** current users details */
                user: appstore.user,
                /** current users projects */
                projects: appstore.projects,
                /** current projects id */
                id: '',
                /** current section id */
                sectionId: '',
                /** form errors */
                errors: new Errors(),
                newTask: new Task({id:'', name:'', tasks: [], created_at: ''}),
                newSection: new Section({id:'', name:'', tasks: [], created_at: ''}),
                editTask:{}
            }
        },
        components:{projectSection, Modal, Datepicker},
        computed: {
            /** current project */
            project: function() {
                let self = this;
                /** get route param and set as id*/
                this.id = this.$route.params.id;
                /** filter projects by id to get current project */
                let project =  this.projects.filter(function (project) {
                    return project.id == self.id;
                });
                /** return project */
                return project[0];
            }
        },
        methods: {
            addSection: function(){
                let self = this;
                axios.post('/api/project/'+ self.project.id +'/section/', self.newSection )
                    .then(function (response) {
                        /** add new task to section array */
                        appstore.addSection(self.project.id, response.data.section);
                        /** toggle addSection modal */
                        Event.$emit('addSection');
                        Event.$emit('addSectionToggleLoading');
                    })
                    .catch(function (error) {
                        /** if error keep modal open and display errors */
                        if(error.response.data){
                            self.errors.record(error.response.data);
                            Event.$emit('addSectionToggleLoading');
                        }
                    });
            },
            addTask: function(){
                let self = this;
                axios.post('/api/project/'+ self.project.id +'/section/' + self.sectionId + '/task', self.newTask )
                    .then(function (response) {
                        /** add new task to section array */
                        appstore.addTask(self.project.id, self.sectionId, response.data.task);
                        /** clear new task object */
                        self.newTask.clear();
                        /** toggle addTask modal */
                        Event.$emit('addTask');
                        /** toggle modal save button loading state  */
                        Event.$emit('addTaskToggleLoading');

                    })
                    .catch(function (error) {
                        /** if error keep modal open and display errors */
                        if(error.response.data){
                            self.errors.record(error.response.data);
                            /** toggle modal save button loading state  */
                            Event.$emit('addTaskToggleLoading');
                        }
                    });
            },
            updateTask: function(){
                let self = this;
                axios.put('/api/project/'+ self.project.id +'/section/' + self.sectionId + '/task/' + self.editTask.id , self.editTask )
                    .then(function (response) {
                        /** update task in array */
                        appstore.updateTask(self.project.id, self.sectionId, response.data.task);
                        /** toggle addTask modal */
                        Event.$emit('updateTask');
                        /** toggle modal save button loading state  */
                        Event.$emit('updateTaskToggleLoading');
                    })
                    .catch(function (error) {
                        /** if error keep modal open and display errors */
                        if(error.response.data){
                            self.errors.record(error.response.data);
                            Event.$emit('updateTaskToggleLoading');
                        }
                    });
            },
            event: function(event){
                Event.$emit(event);
            }
        },
        mounted() {
            let self = this;
            Event.$on('modalSubmit', function(form) {
                switch(form) {
                    case 'addTask':
                        self.addTask();
                        break;
                    case 'addSection':
                        self.addSection();
                        break;
                    case 'updateTask':
                        self.updateTask();
                        break;
                }
            });
            Event.$on('clickedSection', function(id) {
                self.sectionId = id;
            });
            Event.$on('clickedTask', function(task, sectionId) {
                self.editTask = task;
                self.sectionId = sectionId;
            });
        }
    }
</script>
