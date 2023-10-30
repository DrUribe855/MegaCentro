require('./bootstrap');

import Vue from 'vue';
import Vuetify from "vuetify";
 

Vue.use(Vuetify);
 
window.Vue = require('vue');
//Lista de componentes

//Administrator

Vue.component('employees-list', require('./components/Administrator/Employees/EmployeesList.vue').default);
Vue.component('clinic-list', require('./components/Clinic/Clinic.vue').default);
Vue.component('clinic-edit', require('./components/Clinic/EditClinic').default);

//Assistant
Vue.component('index-assistant', require('./components/Assistant/IndexAssistant.vue').default);

//Collector
Vue.component('index-collector', require('./components/Collector/IndexCollector.vue').default);
 
//Manager
Vue.component('index-manager', require('./components/Manager/IndexManager.vue').default);


Vue.component('login', require('./components/Login.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),

});

