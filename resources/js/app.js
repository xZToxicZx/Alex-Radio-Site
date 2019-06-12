import 'es6-promise/auto'
import axios from 'axios'
import './bootstrap'
import Vue from 'vue'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import Notifications from 'vue-notification'


import auth from './auth'
import shared from './shared'
import router from './routes'
import panel from './views/panel/index'
import index from './views/main/index'

Vue.router=router;

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(VueAuth, auth);
Vue.use(Notifications);

Vue.directive('can', {
    bind(el, binding, vnode) {
        let hasPerm = binding.arg+'.'+binding.value;
        let user = vnode.context.$auth.user();
        if (!user)
        {
            vnode.context.$nextTick(() => $(el).remove());
            return;
        }
        hasPerm = user.role.perms.find(perm => {
            return (perm.name == hasPerm || perm.name == "all");
        });

        if (!hasPerm)
            vnode.context.$nextTick(() => $(el).remove());
    }
})

router.beforeEach((to, from, next) => {
    if (to.meta.perm) {
        shared.checkperm(to.meta.perm).then(hasPerm => {
            next(hasPerm);
        }).catch(() => next(false));
    } else
    next()
});

const app = new Vue({
    el: '#app',

    data: {
        site: {
            domain: window.location.hostname,
            name: "",
            paypal: ""
        }
    },

    created: function() {
        axios.get('/api/v1/settings/get')
            .then(({data}) => {
                this.site.name = data["siteName"];
                document.title = this.site.name;
                //this.site.img = data[];
                this.site.paypal = data["paypal"];
            }).catch(err => console.log(err));
        this.$auth.ready();
    },

    router,

    components: {
        index,
        panel
    }
});
