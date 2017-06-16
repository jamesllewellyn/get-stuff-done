import './bootstrap';
import router from './app-routes';
import appstore from './app-store';
import Modal from './components/Modal.vue';
import Errors from './core/Errors';
import Project from './core/Project';
window.Event = new Vue();

const app = new Vue({
    el: '#app',
    router: router,
    data: {
        /** logged in user */
        user: appstore.user,
        /** logged in users projects */
        projects: appstore.projects,
        /** form errors */
        errors: new Errors(),
        /** object from new project form data */
        newProject : new Project({id:'', name:'', sections: [], created_at: ''}),
    },
    components : {
        Modal
    },
    methods: {
        /** get logged in users data */
        getUserData: function() {
            let self = this;
            axios.get('/api/user')
                .then(function (response) {
                    /** set user data in store */
                    appstore.initiateUser(response.data);
                    /** Now we've got the user we can get the users projects */
                    self.getUserProjects();
                })
                .catch(function (error) {
                        console.log(error);
                });
        },
        /** get all logged in users projects */
        getUserProjects: function() {
            let self = this;
            axios.get('/api/user/' + self.user.id + '/projects' )
                .then(function (response) {
                    /** set users project data in store */
                    appstore.setProjectData(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        /** trigger toggle modal event */
        toggelModal: function(modalName){
            Event.$emit(modalName);
        },
        addProject: function(){
            let self = this;
            axios.post('/api/project/', self.newProject )
                .then(function (response) {
                    /** add new Project */
                    appstore.addProject(response.data.project);
                    /** toggle addProject modal */
                    Event.$emit('addProject');
                })
                .catch(function (error) {
                    console.log(error);
                    /** if error keep modal open and display errors */
                    if(error.response.data){
                        self.errors.record(error.response.data);
                    }
                });
        },
    },
    mounted: function () {
        let self = this;
        /** Call method to get user data */
        this.getUserData();
        /** listen for modal form submit events */
        Event.$on('modalSubmit', function(form) {
            switch(form) {
                case 'addProject':
                    self.addProject();
                    break;
            }
        });
    }
});
