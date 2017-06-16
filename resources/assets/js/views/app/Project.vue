<template>
    <div>
        <h1 class="title has-text-centered" v-text="project.name"> </h1>

        <div class="tabs is-centered">
            <ul>
                <li class="is-active"><a>List</a></li>
                <li><a>Calender</a></li>
                <li><a>Chats</a></li>
            </ul>
        </div>

        <div>
            <div class="columns">
                <projectSection v-for="section in project.sections" :name="section.name" :key="section.id" :id="section.id"></projectSection>
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
                newTask: {
                    name: '',
                    due_date:''
                }
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
            addTask: function(){
                let self = this;
                axios.post('/api/project/'+ self.project.id +'/section/' + self.sectionId + '/task', self.newTask )
                    .then(function (response) {
                        /** add new task to section array */
                        appstore.addTask(self.project.id, self.sectionId, response.data.task)
                    })
                    .catch(function (error) {
                        /** if error keep modal open and display errors */
                        if(error.response.data){
                            self.errors.record(error.response.data);
                        }
                    });
            },
        },
        mounted() {
            let self = this;
            Event.$on('modalSubmit', function(form) {
                switch(form) {
                    case 'addTask':
                        self.addTask();
                        break;
                }
            });
            Event.$on('clickedSection', function(id) {
                self.sectionId = id;
            });
        }
    }
</script>
