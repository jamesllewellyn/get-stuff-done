import './bootstrap';
import router from './app-routes';
import store from './store';
import AddProject from './components/modals/AddProject.vue';
import Modal from './components/Modal.vue';
import Task from './components/Task.vue';

window.Event = new Vue();
import { mapState, mapGetters } from 'vuex'
const app = new Vue({
    el: '#app',
    router,
    store,
    computed:
        mapState([
            'projects', 'user'
         ])
    ,
    components : {
        Modal, AddProject, Task
    },
    methods: {
        /** trigger toggle modal event */
        triggerEvent: function(eventName, payload){
            Event.$emit(eventName, payload);
        }
    },
    mounted: function () {
        /** Call method to get user data */
        this.$store.dispatch('LOAD_USER');
        this.$store.dispatch('LOAD_PROJECT_LIST');
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
    }
});
