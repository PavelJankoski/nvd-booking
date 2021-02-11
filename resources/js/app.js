require('./bootstrap');
import Vuex from 'vuex';
import VueRouter from "vue-router";
import router from "./routes";
import storeDefinition from './store';
import Index from "./Index";
import {SemipolarSpinner} from 'epic-spinners';
import moment from "moment";
import StarRating from "./shared/components/StarRating";
import FatalError from "./shared/components/FatalError";
import Success from "./shared/components/Success";
import ValidationError from "./shared/components/ValidationError";
window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(Vuex);

Vue.component('loading-component', SemipolarSpinner);
Vue.component("star-rating", StarRating);
Vue.component("fatal-error", FatalError);
Vue.component("success", Success);
Vue.component("validation-error", ValidationError);

Vue.filter("fromNow", value => moment(value).fromNow());

const store = new Vuex.Store(storeDefinition);

window.axios.interceptors.response.use(
    response => response,
    error => {
        if(error.response.status === 401) {
            store.dispatch('logOut');
        }
        return Promise.reject(error);
    }
)

const app = new Vue({
    el: '#app',
    router: router,
    store,
    components: {
        "index": Index
    },
    async beforeCreate() {
        await this.$store.dispatch("loadStoredState");
        await this.$store.dispatch("patchUser");
    }
});


