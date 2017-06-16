<template>
    <div class="content">
        <h1 class="title has-text-centered">
            {{project.name}}
            <a  @click.prevent.stop="event('addSection')"><i class="fa fa-plus-circle align-vertical" aria-hidden="true"></i></a>
        </h1>

        <div class="tabs is-centered">
            <ul>
                <li class="is-active"><a>Overview</a></li>
                <li><a>Due Today</a></li>
            </ul>
        </div>

        <div>
            <div class="columns is-multiline ">
                <projectSection v-for="section in project.sections" :name="section.name" :key="section.id" :id="section.id" :tasks="section.tasks"></projectSection>
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
                            <textarea class="textarea" placeholder="Textarea" v-model="newTask.note"></textarea>
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
                newSection: new Section({id:'', name:'', tasks: [], created_at: ''})
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
                        console.log(response);
                        /** add new task to section array */
                        appstore.addSection(self.project.id, response.data.section);
                        /** toggle addSection modal */
                        Event.$emit('addSection');
                    })
                    .catch(function (error) {
                        console.log(error);
                        /** if error keep modal open and display errors */
                        if(error.response.data){
                            self.errors.record(error.response.data);
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
                    })
                    .catch(function (error) {
                        console.log(error);
                        /** if error keep modal open and display errors */
                        if(error.response.data){
                            self.errors.record(error.response.data);
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
                }
            });
            Event.$on('clickedSection', function(id) {
                self.sectionId = id;
            });
        }
    }
</script>
