import './bootstrap';
import router from './app-routes';
import store from './store/index';
import AddProject from './components/modals/AddProject.vue';
import AddTeam from './components/modals/AddTeam.vue';
import Modal from './components/Modal.vue';
import areYouSure from './components/AreYouSureModal.vue';
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
        Task, Modal, AddProject, Navigation , Profile, Spinner, AddTeam, areYouSure
    },
    methods: {
        /** listens to Echo channels */
        listen(){
            // /** listens to users current teams projects channel */
            // Echo.channel('teams.'+ this.user.current_team_id + '.projects' )
            //      /** listen for new project being added*/
            //     .listen('ProjectAdded', (e) => {
            //         /** called ADD_PROJECT_SUCCESS to add project to list of projects */
            //         this.$store.commit('ADD_PROJECT_SUCCESS', { project : e.project});
            //     });
            Echo.private('App.User.' + this.user.id)
                .notification((notification) => {
                    console.log(notification);
                    this.$store.commit('NOTIFICATION_ADD', {notification:notification});
                });
        },
        /** trigger event method */
        triggerEvent: function(eventName, payload){
            Event.$emit(eventName, payload);
        }
    },
    watch: {
        user () {
            /** wait for user data before fetching users teams **/
            if(this.user){
                /** get users teams */
                this.$store.dispatch('LOAD_TEAMS');
                /** get user notifications */
                this.$store.dispatch('GET_NOTIFICATIONS');
                /** listen to users Echo channels */
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
        /** listen for notifications */
        Event.$on('notify', function(type, title, text) {
            if (typeof text === 'undefined') {
                text = '';
            }
            this.$notify({
                type: type,
                title: title,
                text: text
            });
        });
        /** listen for toggle navigation events */
        Event.$on('toggleNav', function() {
            store.commit('TOGGLE_NAV_IS_VISIBLE');
        });
        /** listen for toggle profile events */
        Event.$on('toggleProfile', function() {
            store.commit('TOGGLE_PROFILE_IS_VISIBLE');
        });
        /** listen for force change page events */
        Event.$on('changePage', function($route) {
            router.push($route);
        });
    }
});
