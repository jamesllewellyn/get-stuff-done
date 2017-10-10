import VueRouter from 'vue-router';

let routes = [
    {
        path: '',
        redirect: '/user/'
    },
    {
        path: '/user/',
        component: require('./views/auth/RegisterUser.vue'),
    },
    {
        path: '/team/',
        component: require('./views/auth/CreateTeam.vue'),
    }
];

export default new VueRouter({
    routes,
    mode: 'hash'
});
