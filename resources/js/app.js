/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import Vuelidate from 'vuelidate'
import VueRouter from 'vue-router'
Vue.use(VueRouter)
Vue.use(Vuelidate)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.use(BootstrapVue)
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
import Vue from 'vue';
import VueToast from 'vue-toast-notification';

// Import one of the available themes
//import 'vue-toast-notification/dist/theme-default.css';
import 'vue-toast-notification/dist/theme-sugar.css';
import App from './App.vue';
import Contact from './views/Contact.vue';
Vue.use(VueToast);
//Vue.$toast.open({/* options */});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [  
  { 
    path: '/',
    name: 'contact',
    component: Contact,
    meta: { title: 'Contact' }
  },
];
var router = new VueRouter({  
  mode:'history',
  routes
});
router.afterEach((to, from) => {    
    Vue.nextTick(() => {
      document.title = to.meta.title+ ' | Test Task' || 'Test Task';
    });
});
const app = new Vue({
	el: '#app',
  render: h => h(App),
  router,
  components: {'App' : App },
});
