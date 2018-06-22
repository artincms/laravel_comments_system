
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuex = require('vuex');
const trans = window.VueTranslate = require('vue-translate-plugin');
var VueScrollTo = require('vue-scrollto');

Vue.use(Vuex);
Vue.use(VueTranslate);
Vue.use(VueScrollTo, {
    container: "body",
    duration: 1000,
    easing: "ease-in-out",
    offset: -60,
    cancelable: true,
    onStart: false,
    onDone: false,
    onCancel: false,
    x: false,
    y: true
})

const store = new Vuex.Store({
    state: {
        user_id:0,
        quote_id:0,
        canComment:false,
        data_array:[],
        model:[],
    },
})

Vue.component('laravel_comments_system', require('./components/laravel_comments_system.vue'));
window.onload = function () {
    const comments = new Vue({
        el: '#comments',
        store: store,
    });
}