import Vue from 'vue';
import VueRouter from 'vue-router';
import jwtToken from './helpers/jwt-token';

Vue.use(VueRouter);

import Store from './store/index'
import Home from './components/Home/Home.vue'
import Login from './components/Login/Login.vue'
import Dashboard from './components/dashboard/Dashboard.vue'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'index',
            component: Home,
            meta: {}
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: { requiresGuest: true }
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
            meta: { requiresAuth: true }
        }
    ]
});

router.beforeEach((to, from, next) => {
    Store.dispatch('hideErrorNotification');

    if(to.meta.requiresAuth) {
        if(Store.state.authUser.authenticated || jwtToken.getToken())
            return next();
        else
            return next({name: 'login'});
    }
    if(to.meta.requiresGuest) {
        if(Store.state.authUser.authenticated || jwtToken.getToken())
            return next({name: 'index'});
        else
            return next();
    }
    next();
});

export default router;