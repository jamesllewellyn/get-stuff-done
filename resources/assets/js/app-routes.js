import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./views/app/Home.vue')
    },
    {
        path: '/home/',
        component: require('./views/app/Home.vue')
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
