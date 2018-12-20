
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.axios = require('axios');

window.Router = require('vue-router');

window.Laravel = "<?php echo json_encode(['csrfToken' => csrf_token(),'baseUrl' => base_url()]); ?>";

axios.defaults.baseURL = $baseUrl;
import VueRouter from 'vue-router';
Vue.use(VueRouter);


import router from './routes.js';

import { EventBust }  from './event-bus.js';


// window.BootstrapVue = require('bootstrap-vue');
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap-vue/dist/bootstrap-vue.css';

//import 'bootstrap/dist/css/bootstrap.min.css'

import * as uiv from 'uiv';

Vue.use(uiv);

//Vue.http.options.root = 'http://localhost:8080/brsVue/public/';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
// Vue.component('users', require('./components/Users.vue'));
// Vue.component('user-create',require('./components/Create.vue'));
// Vue.component('user-edit',require('./components/Edit.vue'));

/*
* finance Components
* */

    Vue.component('bank-list',require('./DTR/finance/Bank.vue'));
    Vue.component('modal-table',require('./DTR/finance/modalComponent/table.vue'));
    Vue.component('not-equal',require('./DTR/finance/error/notEqual.vue'));
    Vue.component('error-list',require('./DTR/finance/error/errorList.vue'));
    Vue.component('side-bar',require('./DTR/finance/layouts/sidebar.vue'));
    Vue.component('bank-table',require('./DTR/finance/bankData/perDateTable.vue'));
    Vue.component('monthly-table',require('./DTR/finance/bankData/table.vue'));

/*
* Accounting Components
* */
    Vue.component('bank-list-acct',require('./DTR/accounting/Bank.vue'));
    Vue.component('bank-table-acct',require('./DTR/accounting/bankData/perDateTable.vue'));
    Vue.component('monthly-table-acct',require('./DTR/accounting/bankData/table.vue'));

const app = new Vue({
    el: '#app',
    router,
});
