import VueRouter from 'vue-router';

let routes = [

    {
        path: '/',

        name: 'Home',

        component: require('./views/main/home.vue').default,

        meta: {
            auth: undefined
        }
    },
    {
        path: '/signin',

        component: require('./views/main/signin.vue').default,

        meta: {
            auth: false
        }
    },
    {
        path: '/team',

        component: require('./views/main/team.vue').default,

        meta: {
            auth: undefined
        }
    },
    {
        path: '/dannyb',

        component: require('./views/main/dannyb.vue').default,

        meta: {
            auth: undefined
        }
    },
    {
        path: '/partners',

        component: require('./views/main/partners.vue').default,

        meta: {
            auth: undefined
        }
    },
    {
        path: '/schedule',

        component: require('./views/main/schedule.vue').default,

        meta: {
            auth: undefined
        }
    },
    {
        path: '/register',

        component: require('./views/main/register.vue').default,

        meta: {
            auth: false
        }
    },
    {
        path: '/settings',

        component: require('./views/main/settings.vue').default,

        meta: {
            auth: true
        }
    },
    {
        path: '/panel/',

        component: require('./views/panel/home.vue').default,

        meta: {
            auth: true,
            perm: 'view.panel'
        }
    },
    {
        path: '/panel/requests',

        component: require('./views/panel/requests.vue').default,

        meta: {
            auth: true,
            perm: 'view.panel.requests'
        }
    },
    {
        path: '/panel/timetable',

        component: require('./views/panel/timetable.vue').default,

        meta: {
            auth: true,
            perm: 'view.panel.timetable'
        }
    },
    {
        path: '/panel/settings',

        component: require('./views/panel/settings.vue').default,

        meta: {
            auth: true,
            perm: 'view.panel.settings'
        }
    },
    {
        path: '/panel/users',

        component: require('./views/panel/users.vue').default,

        meta: {
            auth: true,
            perm: 'view.panel.users'
        }
    },
    {
        path: '/panel/roles',

        component: require('./views/panel/roles.vue').default,

        meta: {
            auth: true,
            perm: 'view.panel.roles'
        }
    },
    {
        path: '/panel/sitesettings',

        component: require('./views/panel/sitesettings.vue').default,

        meta: {
            auth: true,
            perm: 'view.panel.sitesettings'
        }
    }

];


export default new VueRouter({

    mode: 'history',

    routes,

    linkActiveClass: 'active'

});
