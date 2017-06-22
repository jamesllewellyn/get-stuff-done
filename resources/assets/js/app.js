import './bootstrap';
import router from './app-routes';
import store from './store';
// import appstore from './app-store';
import AddProject from './components/modals/AddProject.vue';
import Modal from './components/Modal.vue';
import Errors from './core/Errors';
import Project from './core/Project';
window.Event = new Vue();
import { mapState, mapGetters } from 'vuex'
const app = new Vue({
    el: '#app',
    router,
    store,
    data: {
    //     /** logged in user */
    //     user: appstore.user,
    //     /** logged in users projects */
    //     projects: appstore.projects,
    //     /** form errors */
    //     errors: new Errors(),
    //     /** object from new project form data */
    //     newProject : new Project({id:'', name:'', sections: [], created_at: ''}),
    },
    computed:
        mapState([
            'projects', 'user'
         ])
    ,
    components : {
        Modal, AddProject,
    },
    methods: {
        /** get logged in users data */
        // getUserData: function() {
        //     let self = this;
        //     axios.get('/api/user')
        //         .then(function (response) {
        //             /** set user data in store */
        //             appstore.initiateUser(response.data);
        //             /** Now we've got the user we can get the users projects */
        //             self.getUserProjects();
        //         })
        //         .catch(function (error) {
        //                 console.log(error);
        //         });
        // },
        // /** get all logged in users projects */
        // getUserProjects: function() {
        //     let self = this;
        //     axios.get('/api/user/' + self.user.id + '/projects' )
        //         .then(function (response) {
        //             /** set users project data in store */
        //             appstore.setProjectData(response.data);
        //         })
        //         .catch(function (error) {
        //             console.log(error);
        //         });
        // },
        /** trigger toggle modal event */
        triggerEvent: function(eventName, payload){
            Event.$emit(eventName, payload);
        }
        // addProject: function(){
        //     let self = this;
        //     axios.post('/api/project/', self.newProject )
        //         .then(function (response) {
        //             /** add new Project */
        //             appstore.addProject(response.data.project);
        //             /** toggle addProject modal */
        //             Event.$emit('addProject');
        //             Event.$emit('addProjectToggleLoading');
        //         })
        //         .catch(function (error) {
        //             /** if error keep modal open and display errors */
        //             if(error.response.data){
        //                 self.errors.record(error.response.data);
        //                 Event.$emit('addProjectToggleLoading');
        //             }
        //         });
        // },
    },
    mounted: function () {
        // let self = this;
        /** Call method to get user data */
        this.$store.dispatch('LOAD_USER');
        this.$store.dispatch('LOAD_PROJECT_LIST');
        /** listen for modal toggle events */
        Event.$on('toggleModal', function(modalName) {
            store.commit('TOGGLE_MODAL_IS_VISIBLE', {name : modalName});
        });
        // /** trigger success sweet alert */
        // Event.$on('swalSuccess', function(message) {
        //     self.$swal({title: "Success",
        //         text:  message,
        //         timer: 2000,
        //         type: "success",
        //         showConfirmButton: false }).then(
        //         function () {},
        //         function (dismiss) {
        //             if (dismiss === 'timer') {
        //             }
        //         })
        // });
    }
});
