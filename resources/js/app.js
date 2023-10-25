require('./bootstrap');

import Vue from 'vue';
import Vuetify from "vuetify";

Vue.use(Vuetify);
 
window.Vue = require('vue');
//Lista de componentes

Vue.component('employees-list', require('./components/Administrator/EmployeesList.vue').default);
Vue.component('modify-employee', require('./components/Administrator/ModifyEmployee.vue').default);
// Clinic
Vue.component('clinic-list', require('./components/Clinic/Clinic.vue').default);
Vue.component('clinic-edit', require('./components/Clinic/EditClinic').default);
Vue.use(Vuetify);
Vue.component('login', require('./components/Login.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});

