require('./bootstrap');
window.Vue = require('vue');


import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

// pagination support
// Vue.component('pagination', require('laravel-vue-pagination'))
// support markdown
// import 'v-markdown-editor/dist/v-markdown-editor.css';
// import Editor from 'v-markdown-editor'

// global register
// Vue.use(Editor);
// Support vuex
import Vuex from 'vuex'
Vue.use(Vuex)
import storeData from "./store/index"
const store = new Vuex.Store(
    storeData
)

// Support Router
// import VueRouter from 'vue-router'
// Vue.use(VueRouter)
// import {routes} from './routes';

// Support fontawesome
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'

// support moment js
import { filter} from './filter'

// Vue.component('example-component', require('./components/admin/adminHome.vue'));
 Vue.component('main-app', require('./components/MainApp.vue').default);


// // Support V-form
import { Form, HasError, AlertError } from 'vform'
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
window.Form = Form;
// sweet alert
import swal from 'sweetalert2'
window.swal = swal;
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast

// const router = new VueRouter({
//   routes, 
//   mode:'hash'
// })

const app = new Vue({
    el: '#app',
    store
});



