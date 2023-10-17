require('./bootstrap');

import Vue from 'vue';

window.Vue = require('vue');

//Lista de componentes

Vue.component('employees-list', require('./components/Administrator/EmployeesList.vue').default);
Vue.component('modify-employee', require('./components/Administrator/ModifyEmployee.vue').default);
Vue.component('clinic-list', require('./components/Clinic/Clinic.vue').default);

const app = new Vue({
    el: '#app',
});

