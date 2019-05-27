import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";
import VueEvents from 'vue-events';

import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'


import {routes} from "./routes";
import StoreDate from "./store";
import MainApp from "./components/MainApp.vue";

import {initialize} from './helpers/general';

require('./bootstrap');

window.Vue = Vue;

Vue.use(ElementUI, { locale });

Vue.use(VueEvents);
Vue.use(VueRouter);
Vue.use(Vuex);
const store = new Vuex.Store(StoreDate);
const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

const app = new Vue({
    el: '#app',
    router,
    store,
    components:{
        MainApp
    }
});
