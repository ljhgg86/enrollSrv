const routes = [
    { path: '/', component: myList},
    { path: '/activity', component: activity }
];
const router = new VueRouter({
    routes // (缩写) 相当于 routes: routes
  });
const pp = new Vue({
    router
  }).$mount('#ff');
