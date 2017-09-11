import VueRouter from 'vue-router';

let routes = [
    // {
    //     path: '',
    //     redirect: '/team/'
    // },
    // {
    //     path: '/team/',
    //     component: require('./views/auth/CreateAccount.vue'),
    //     children: [
    //         {
    //             path: '',
    //             redirect: 'user'
    //         },
    //         {
    //             path: 'user',
    //             component: require('./views/auth/account-steps/MakeUser.vue')
    //         },
    //         {
    //             path: 'set-up-team',
    //             component: require('./views/auth/account-steps/SetUpTeam.vue')
    //         }
    //     ]
    // }
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
