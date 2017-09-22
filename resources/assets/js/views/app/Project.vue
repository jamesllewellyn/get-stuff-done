<template>
    <div class="project">
        <div class="level header is-mobile">
            <div class="level-left">
                <drop-down-button :boarder="false" :dropdowns="[{text : 'Delete Project', event: 'project.'+id+'.delete', action: 'delete this project', areYouSure : true}]"></drop-down-button>
                <input class="title clear-background h1" type="text" name="name" @change="updateProject" v-model="project.name" v-if="project.name != ''" v-cloak>
                <h1 v-else class="blokk title" >Project Title</h1>
            </div>
            <div class="level-right">
                <div class="has-text-right">
                    <span class="button is-orange">
                        <a  @click.prevent.stop="triggerEvent('toggleModal','addSection')" class="orange">Add Section</a>
                    </span>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <div class="columns is-multiline" v-if="project">
                <!--<transition-group tag="template" name="fade" mode="out-in">-->
                    <project-section v-for="(section, key) in project.sections" :projectId="id" :id="section.id" :sectionName="section.name" :key="key" ></project-section>
                    <project-section  v-if="project.sections.length == 0" :placeHolder="true"></project-section>
                <!--</transition-group>-->
            </div>
        </div>
        <!--
        *************
        *  Modals
        *************
        -->
        <modal modalName="addSection" title="Add New Section">
            <template slot="body">
                <add-section :projectId="id"></add-section>
            </template>
        </modal>

        <modal modalName="addTask" title="Add New Task">
            <template slot="body">
                <add-task :projectId="id" :sectionId="sectionId"></add-task>
            </template>
        </modal>

    </div>
</template>

<script>
    import store from '../../store';
    import addSection from '../../components/modals/AddSection.vue';
    import addTask from '../../components/modals/AddTask.vue';
    import updateTask from '../../components/modals/UpdateTask.vue';
    import projectSection from '../../components/Section';
    import dropDownButton from '../../components/DropDownButton.vue';
    import modal from '../../components/Modal.vue';
    export default {
        data() {
            return{
                sectionId: '',
                taskId:''
            }
        },
        components:{projectSection, modal, addSection , addTask, updateTask, dropDownButton},
        computed: {
            id: function(){
                if(!this.$route.params.id){
                    return false;
                }
                return parseInt(this.$route.params.id);
            },
            project () { return this.$store.getters.getProject },
            TeamId: function(){
                return store.getters.getActiveTeam.id;
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
            deleteProject(){
                this.$store.dispatch('DELETE_PROJECT', {id: this.id})
            }
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
            /** listen for project delete event **/
            Event.$on('project.'+this.id+'.delete', function() {
                self.deleteProject();
            });
        },
        beforeRouteUpdate (to, from, next) {
            this.$store.commit('CLEAR_PROJECT');
            next();
        },
    }
</script>
