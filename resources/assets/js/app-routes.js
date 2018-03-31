import VueRouter from 'vue-router';

let routes = [
    {
        path: '',
        redirect: '/inbox/'
    },
    {
        path: '/',
        redirect: '/inbox/'
    },
    {
        path: '/inbox/',
        component: require('./views/app/Inbox.vue').default
    },
    {
        path: '/my-tasks/',
        component: require('./views/app/MyTasks.vue').default
    },
    {
        path: '/over-due/',
        component: require('./views/app/OverDue.vue').default
    },
    { path: '/project/:id', component: require('./views/app/Project.vue').default,
        children: [
            {
                path: '',
                component: require('./views/app/Project.vue').default
            }
        ]
    },
    {
        path: '/team-dashboard/',
        component: require('./views/app/TeamDashboard.vue').default
    },
];

export default new VueRouter({
    routes,
});
