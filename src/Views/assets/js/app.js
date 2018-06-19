
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
var VueScrollTo = require('vue-scrollto');
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

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('laravel_comments_system', require('./components/frontendComment.vue'));
// define the item component
Vue.component('laravel_comments_system', require('./components/laravel_comments_system.vue'));
window.onload = function () {
   const comments = new Vue({
        el: '#comments'
    });
}