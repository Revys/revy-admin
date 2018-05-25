
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


// Import vendor
// ======================================================================
Vue.use(require('vue-prevent-parent-scroll'));

require('fontawesome-iconpicker');
window.Sortable = require('sortablejs');

// Load page scripts
require('./ajax');

require('./page');

require('./directives/click-outside');

require('./components/Transition.js');
require('./components/select.js');
require('./components/editor.js');