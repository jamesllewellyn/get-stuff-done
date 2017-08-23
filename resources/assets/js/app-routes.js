import VueRouter from 'vue-router';

let routes = [
    {
        path: '/team/',
        component: require('./views/auth/CreateAccount.vue'),
        children: [
            {
                path: '',
                redirect: 'email'
            },
            {
                path: 'email',
                component: require('./views/auth/account-steps/Email.vue')
            },
            {
                path: 'set-up-team',
                component: require('./views/auth/account-steps/SetUpTeam.vue')
            },
            {
                path: 'user',
                component: require('./views/auth/account-steps/MakeUser.vue')
            }
        ]
    },
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
    }
];

export default new VueRouter({
    routes,
    mode: 'hash'
});
