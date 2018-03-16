import VueRouter from 'vue-router';

let routes = [
    {
        path: '',
        redirect: '/user/'
    },
    {
        path: '/user/',
        component: require('./views/auth/RegisterUser.vue').default,
    },
    {
        path: '/team/',
        component: require('./views/auth/CreateTeam.vue').default,
    }
];

export default new VueRouter({
    routes,
    mode: 'hash'
});
