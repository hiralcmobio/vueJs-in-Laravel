require('./bootstrap');

window.Vue = require('vue');
Vue.config.devtools = false;

Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('articles', require('./components/articles.vue').default);
Vue.component('users', require('./components/Users.vue').default);

const app = new Vue({
    el: '#app'
});
