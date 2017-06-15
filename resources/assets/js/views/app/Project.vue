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
                <projectSection v-for="section in project.sections" :name="section.name" :key="section.id"></projectSection>
            </div>
        </div>
        <Modal modalName="addTask" title="Add New Task">
            <div slot="body">
                <form>
                    <div class="field">
                        <label class="label">Task</label>
                        <p class="control">
                            <input class="input" type="text" name="task" placeholder="Task Name">
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
    import Modal from '../../components/Modal.vue';
    import Section from '../../core/Section';
    export default {
        data() {
            return{
                /** current users details */
                user: appstore.user,
                /** current users projects */
                projects: appstore.projects,
                /** current projects id */
                id: ''
            }
        },
        components:{projectSection, Modal},
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

        },
        mounted() {
            let self = this;
        }
    }
</script>
