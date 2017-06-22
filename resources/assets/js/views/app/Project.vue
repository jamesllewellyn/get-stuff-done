<template>
    <div class="container">
        <h1 class="title" v-if="project.name" >{{project.name}}</h1>
        <div class="has-text-right">
            <span class="tag is-orange is-medium">
                <a  @click.prevent.stop="triggerEvent('toggleModal','addSection')" class="orange">Add Section</a>
            </span>
        </div>
        <hr>
        <div>
            <div class="columns is-multiline">
                <project-section v-for="section in project.sections" :id="section.id" :key="section.id" :projectId="id"></project-section>
            </div>
        </div>
        <!--
        *************
        *  Modals
        *************
        -->
        <Modal modalName="addTask" title="Add New Task">
            <template slot="body">
                <add-task :projectId="id" :sectionId="sectionId"></add-task>
            </template>
        </Modal>

        <Modal modalName="addSection" title="Add New Section">
            <template slot="body">
                <add-section :projectId="id"></add-section>
            </template>
        </Modal>

        <Modal modalName="updateTask" title="Task">
            <template slot="body">
                <update-task :projectId="id" :sectionId="sectionId" :id="taskId"></update-task>
            </template>
        </Modal>
    </div>
</template>

<script>
//    import appstore from '../../app-store';
    import store from '../../store';
    import AddSection from '../../components/modals/AddSection.vue';
    import AddTask from '../../components/modals/AddTask.vue';
    import UpdateTask from '../../components/modals/UpdateTask.vue';
    import ProjectSection from '../../components/Section';
    import Modal from '../../components/Modal.vue';
    import Section from '../../core/Section';
    import Task from '../../core/Task';
    export default {
        data() {
            return{
                /** current section id */
                id:'',
                sectionId: '',
                taskId:''
            }
        },
        components:{ProjectSection, Modal, AddSection , AddTask, UpdateTask},
        computed: {
            project: function() {
                /** get  project via route param */
                return store.getters.getProjectById(this.$route.params.id);
            }
        },
        methods: {

//            updateTask: function(){
//                let self = this;
//                axios.put('/api/project/'+ self.project.id +'/section/' + self.sectionId + '/task/' + self.editTask.id , self.editTask )
//                    .then(function (response) {
//                        /** update task in array */
//                        appstore.updateTask(self.project.id, self.sectionId, response.data.task);
//                        /** toggle addTask modal */
//                        Event.$emit('updateTask');
//                        /** toggle modal save button loading state  */
//                        Event.$emit('updateTaskToggleLoading');
//                    })
//                    .catch(function (error) {
//                        /** if error keep modal open and display errors */
//                        if(error.response.data){
//                            self.errors.record(error.response.data);
//                            Event.$emit('updateTaskToggleLoading');
//                        }
//                    });
//            },
            /** trigger event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            }
        },
        mounted() {
            let self = this;
            /** set id from route param **/
            this.id = this.$route.params.id;
            Event.$on('clickedSection', function(id) {
                self.sectionId = id;
            });
            Event.$on('clickedTask', function(sectionId, taskId) {
                self.taskId = taskId;
                self.sectionId = sectionId;
            });
        }
    }
</script>
