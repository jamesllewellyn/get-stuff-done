import './bootstrap';
import router from './app-routes';
import store from './store/index';
import AddProject from './components/modals/AddProject.vue';
import AddTeam from './components/modals/AddTeam.vue';
import Modal from './components/Modal.vue';
import Task from './components/Task.vue';
import Profile from './components/Profile.vue';
import Navigation from './components/Nav.vue';
import Spinner from 'vue-simple-spinner'
window.Event = new Vue();
import { mapState, mapGetters } from 'vuex'
const app = new Vue({
    el: '#app',
    router,
    store,
    computed:
        mapState([
           'user', 'navVisible', 'profileVisible', 'teams', 'isLoading'
         ])
    ,
    components : {
        Task, Modal, AddProject, Navigation , Profile, Spinner, AddTeam
    },
    methods: {
        /** listens to Echo channels */
        listen(){
            console.log('listening');
            console.log('teams.'+ this.user.current_team_id + '.projects');
            /** listens to users current teams projects channel */
            Echo.channel('teams.'+ this.user.current_team_id + '.projects' )
                 /** listen for new project being added*/
                .listen('ProjectAdded', (e) => {
                    console.log('ProjectAdded');
                    console.log(e);
                    /** called ADD_PROJECT_SUCCESS to add project to list of projects*/
                    this.$store.commit('ADD_PROJECT_SUCCESS', { project : e.project});
                });
        },
        /** trigger event */
        triggerEvent: function(eventName, payload){
            Event.$emit(eventName, payload);
        }
    },
    watch: {
        user () {
            /** wait for user data before fetching users teams **/
            if(this.user){
                this.$store.dispatch('LOAD_TEAMS');
                /** listen to Echo channels */
                this.listen();
            }
        }
    },
    mounted: function () {
        /** show ajax loader */
        this.$store.commit('SET_IS_LOADING');
        /** Call method to get user data */
        this.$store.dispatch('LOAD_USER');
        /** listen for modal toggle events */
        Event.$on('toggleModal', function(modalName) {
            store.commit('TOGGLE_MODAL_IS_VISIBLE', {name : modalName});
        });
        Event.$on('notify', function(type, title, text) {
            this.$notify({
                type:type,
                title: title,
                text: text
            });
        });
        Event.$on('toggleNav', function() {
            store.commit('TOGGLE_NAV_IS_VISIBLE');
        });
        Event.$on('toggleProfile', function() {
            store.commit('TOGGLE_PROFILE_IS_VISIBLE');
        });
        Event.$on('changePage', function($route) {
            router.push($route);
        });
    }
});
