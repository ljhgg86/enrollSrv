window.Vue = require('vue');
import VueRouter from 'vue-router'
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import store from './store'

Vue.use(VueRouter);
Vue.use(ElementUI);

const myList = Vue.component('mylist', require('./components/myList.vue').default);
const activity = Vue.component('activity', require('./components/activity.vue').default);

const routes = [
    { path: '/', component: myList},
    { path: '/activity', component: activity}
];

const router = new VueRouter({
    routes
  });

const pp = new Vue({
    el:'#ff',
    router,
    store
  })
