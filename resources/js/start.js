window.Vue = require('vue');
import VueRouter from 'vue-router'
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import store from './store'
import global_ from './components/global_'
Vue.prototype.GLOBAL = global_

Vue.use(VueRouter);
Vue.use(ElementUI);

const myList = Vue.component('mylist', require('./components/myList.vue').default);
const activity = Vue.component('activity', require('./components/activity.vue').default);
const checkList = Vue.component('checklist', require('./components/checkList.vue').default);
const checkActivityList = Vue.component('checklist', require('./components/checkActivityList.vue').default);

const routes = [
    { path: '/', component: myList},
    { path: '/activity', component: activity},
    { path: '/checklist', component: checkList},
    { path: '/checkactivitylist', component: checkActivityList}
];

const router = new VueRouter({
    routes
  });

const pp = new Vue({
    el:'#ff',
    router,
    store
  })
