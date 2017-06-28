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
        <task></task>
    </div>
</template>

<script>
    import store from '../../store';
    import AddSection from '../../components/modals/AddSection.vue';
    import AddTask from '../../components/modals/AddTask.vue';
    import UpdateTask from '../../components/modals/UpdateTask.vue';
    import ProjectSection from '../../components/Section';
    import Modal from '../../components/Modal.vue';
    import Task from '../../components/Task.vue'
    export default {
        data() {
            return{
                sectionId: '',
                taskId:''
            }
        },
        components:{ProjectSection, Modal, AddSection , AddTask, UpdateTask,Task},
        computed: {
            id: function(){
                return this.$route.params.id;
            },
            project: function() {
                /** get  project via route param */
                return store.getters.getProjectById(this.id);
            }
        },
        methods: {
            /** trigger event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            }
        },
        mounted() {
            let self = this;

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
