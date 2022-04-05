import Vue from 'vue';

require('./bootstrap');

import "./filters";

import globalComponent from './globalComponent';

import { Datetime } from 'vue-datetime';

import 'vue-datetime/dist/vue-datetime.css';

Vue.component('datetime', Datetime);

import { Form, HasError, AlertError } from 'vform';
import objectToFormData from 'object-to-formdata'

window.Form = Form;
window.objectToFormData = objectToFormData;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import Swal from 'sweetalert2';
const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3000,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Swal = Swal;
window.Toast = Toast;

import VueProgressBar from 'vue-progressbar';
Vue.use(VueProgressBar, {
    color: 'rgb(27,255,109)',
    failedColor: 'red',
    thickness: '10px'
});

Vue.component('pagination', require('laravel-vue-pagination'));

import InputTag from 'vue-input-tag';

Vue.component('input-tag', InputTag);

const app = new Vue({
    el: '#app'
});
