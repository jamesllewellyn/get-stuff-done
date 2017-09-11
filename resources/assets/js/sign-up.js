import './bootstrap';
import router from './sign-up-routes';
import store from './store';
import { mapState, mapGetters } from 'vuex'
window.Event = new Vue();
const app = new Vue({
    el: '#app',
    router,
    store,
    computed:
        mapState([
            'teams'
        ])
    ,
    components : {
    },
    methods: {
        /** trigger event */
        triggerEvent: function(eventName, payload){
            Event.$emit(eventName, payload);
        }
    },
    watch: {
        teams () {
            if(this.teams){
                window.location.href = '/home';
            }
        }
    },
    mounted: function () {
        /** Call method to get user data */
        this.$store.dispatch('LOAD_USER');
        Event.$on('create-team-page', function($page){
            router.push($page);
        })
    }});
