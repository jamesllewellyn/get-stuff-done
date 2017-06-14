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
    { path: '/project/:id', component: require('./views/app/Profile'),
        children: [
            {
                // UserProfile will be rendered inside User's <router-view>
                // when /user/:id/profile is matched
                path: '',
                component: require('./views/app/Profile')
            }
        ]
    }
];

export default new VueRouter({
    routes,
    mode: 'hash'
});
