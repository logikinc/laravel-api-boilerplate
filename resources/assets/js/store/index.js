import Vue from 'vue';
import Vuex from 'vuex';

import notification from "./modules/notification";
import authUser from "./modules/auth-user";
import login from "./modules/login";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        notification,
        authUser,
        login,
    },
    strict: true
});