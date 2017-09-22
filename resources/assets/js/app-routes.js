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
        component: require('./views/app/Inbox.vue')
    },
    {
        path: '/my-tasks/',
        component: require('./views/app/MyTasks.vue')
    },
    {
        path: '/over-due/',
        component: require('./views/app/OverDue.vue')
    },
    { path: '/project/:id', component: require('./views/app/Project.vue'),
        children: [
            {
                path: '',
                component: require('./views/app/Project.vue')
            }
        ]
    },
    {
        path: '/team-dashboard/',
        component: require('./views/app/TeamDashboard.vue')
    },
];

export default new VueRouter({
    routes,
});
