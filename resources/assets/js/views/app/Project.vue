<template>
    <div class="project">
        <div class="level header is-mobile">
            <div class="level-left">
                <input class="title clear-background h1" type="text" name="name" @change="updateProject" v-model="project.name" v-if="project.name != ''" v-cloak>
                <h1 v-else class="blokk title" >Project Title</h1>
            </div>
            <div class="level-right">
                <div class="has-text-right">
                    <span class="tag is-orange is-medium">
                        <a  @click.prevent.stop="triggerEvent('toggleModal','addSection')" class="orange">Add Section</a>
                    </span>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <div class="columns is-multiline" v-if="project">
                <project-section v-for="section in project.sections" :id="section.id" :key="section.id" :projectId="id"></project-section>
                <project-section  v-if="project.sections.length == 0" :placeHolder="true"></project-section>
            </div>
        </div>
        <!--
        *************
        *  Modals
        *************
        -->
        <Modal modalName="addSection" title="Add New Section">
            <template slot="body">
                <add-section :projectId="id"></add-section>
            </template>
        </Modal>

        <Modal modalName="addTask" title="Add New Task">
            <template slot="body">
                <add-task :projectId="id" :sectionId="sectionId"></add-task>
            </template>
        </Modal>

        <Modal modalName="updateTask" title="Task">
            <template slot="body">
                <!--<update-task :projectId="id" :sectionId="sectionId" :id="taskId"></update-task>-->
            </template>
        </Modal>
    </div>
</template>

<script>
    import store from '../../store';
    import AddSection from '../../components/modals/AddSection.vue';
    import AddTask from '../../components/modals/AddTask.vue';
    import UpdateTask from '../../components/modals/UpdateTask.vue';
    import ProjectSection from '../../components/Section';
    import Modal from '../../components/Modal.vue';
    export default {
        data() {
            return{
                sectionId: '',
                taskId:''
            }
        },
        components:{ProjectSection, Modal, AddSection , AddTask, UpdateTask},
        computed: {
            id: function(){
                if(!this.$route.params.id){
                    return false;
                }
                return parseInt(this.$route.params.id);
            },
            project: function() {
                /** get project */
                return store.getters.getProject;
            },
            TeamId: function(){
                return store.getters.getActiveTeam;
            }
        },
        methods: {
            /** trigger event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            updateProject:function(){
                this.$store.dispatch('UPDATE_PROJECT', {id: this.id, project :this.project})
            },
        },
        watch: {
            /** whenever id changes, get project data */
            id () {
                /** dispatch action */
                if(this.id){
                    this.$store.dispatch('GET_PROJECT', {id: this.id});
                }
            },
            /** whenever TeamId changes, get project data */
            TeamId(){
                this.$store.dispatch('GET_PROJECT', {id: this.id});
            }
        },
        created() {
            let self = this;
            /** get project sections and tasks **/
            if(this.TeamId){
                this.$store.dispatch('GET_PROJECT', {id: this.id});
            }
            /** get project sections and tasks **/
            Event.$on('clickedSection', function(id) {
                self.sectionId = id;
            });

            Event.$on('clickedTask', function(sectionId, taskId) {
                self.taskId = taskId;
                self.sectionId = sectionId;
            });
        },
        beforeRouteUpdate (to, from, next) {
            this.$store.commit('CLEAR_PROJECT');
            next();
        },
    }
</script>
