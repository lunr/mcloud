
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('../../../node_modules/footable/dist/footable.all.min');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//
// Vue.component('example', require('./components/Example.vue'));
//
// const app = new Vue({
//     el: '#app'
// });

$(document).ready(function() {
    $('body').on('click', '.js-add-movie', function(e) {
        e.preventDefault();

        var btn = $(this);
        var added_btn = $(this).siblings('.js-added-movie');
        var endpoint = $(this).attr('href');

        $.ajax(endpoint, {
            contentType: 'application/json',
            method: 'GET',
            success: function(response) {
                $(btn).fadeOut('fast', function() {
                    $(added_btn).fadeIn('fast');
                    $(btn).remove();
                });
            },
            error: function(resp) {
                alert('Error adding movie to your collection!');
            }
        });
    });
});
