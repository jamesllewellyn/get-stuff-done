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
        Event.$on('create-team-page', function($page){
            router.push($page);
        })
    }
});
