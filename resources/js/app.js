require('./bootstrap');

import Vue from 'vue';
import Vuetify from "vuetify";

window.Vue = require('vue');
//Lista de componentes

Vue.component('employees-list', require('./components/Administrator/EmployeesList.vue').default);
Vue.component('modify-employee', require('./components/Administrator/ModifyEmployee.vue').default);
// Clinic
Vue.component('clinic-list', require('./components/Clinic/Clinic.vue').default);
Vue.component('clinic-edit', require('./components/Clinic/EditClinic').default);
Vue.use(Vuetify);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});

