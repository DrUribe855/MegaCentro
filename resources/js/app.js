require('./bootstrap');

import Vue from 'vue';
import Vuetify from "vuetify";


Vue.use(Vuetify);

window.Vue = require('vue');

Vue.component('index', require('./components/Index.vue').default);

// Clinic
Vue.component('clinic-list', require('./components/Clinic/Responsible.vue').default);
Vue.component('clinic-edit', require('./components/Clinic/Clinic.vue').default);
Vue.component('clinic-show', require('./components/Clinic/ViewClinic.vue').default);
Vue.component('clinic-tower', require('./components/Clinic/Tower.vue').default);
Vue.component('clinic-invoice', require('./components/Clinic/Invoice.vue').default);
//Administrator

Vue.component('employees-list', require('./components/Administrator/Employees/EmployeesList.vue').default);

//Assistant
Vue.component('index-assistant', require('./components/Assistant/IndexAssistant.vue').default);
Vue.component('continuation-assistant', require('./components/Assistant/Continuation.vue').default);
Vue.component('unified-document', require('./components/Assistant/Unified.vue').default);
Vue.component('unified-continuation', require('./components/Assistant/UnifiedContinuation.vue').default);

//Collector
Vue.component('index-collector', require('./components/Collector/IndexCollector.vue').default);
Vue.component('non-hazardous-vue', require('./components/Collector/NonhazardousWaste.vue').default);
Vue.component('residue-chemical-vue', require('./components/Collector/ResidueChemical.vue').default);

//Manager
Vue.component('index-manager', require('./components/Manager/IndexManager.vue').default);
Vue.component('collector-residue', require('./components/Manager/CollectorResidue.vue').default);
Vue.component('residue-price', require('./components/Manager/ResidePrice.vue').default);

Vue.component('login', require('./components/Login.vue').default);

Vue.component('collector-index-admin', require('./components/Collector/CollectorAdminIndex.vue').default);


const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),

});

