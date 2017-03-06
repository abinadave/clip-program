
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
require('./bootstrap');
Vue.use(VueRouter);
Vue.use(VueResource);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 
// Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf-token').getAttribute('content');

import GrantComp from './components/grant/grant.vue'
import TypesComp from './components/assistance-types/assitance-types.vue'
import GrantedComp from './components/granted/granted.vue'
window.router = new VueRouter({
  routes: [
    { 
      path: '/', 
      redirect: '/assistance/granted' 
    },
    {
	    path: '/grant',
	    component: GrantComp
    },
    {
    	path: '/assistance/type',
    	component: TypesComp
    },
    {
      path: '/assistance/granted',
      component: GrantedComp
    }
  ]
});

const app = new Vue({
  	router
}).$mount('#app');

