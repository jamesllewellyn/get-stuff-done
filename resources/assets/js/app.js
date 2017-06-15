import './bootstrap';
import router from './app-routes';
import appstore from './app-store';
window.Event = new Vue();

const app = new Vue({
    el: '#app',
    router: router,
    data: {
        user: appstore.user,
        projects: appstore.projects
    },
    components : {

    },
    methods: {
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
        }
    },
    mounted: function () {
        /** Call method to get user data */
        this.getUserData();
    }
});
