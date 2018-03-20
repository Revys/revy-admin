// Import Core
import Form from './core/Form'
window.Form = Form;

import Popup from './components/Popup.vue'
window.Popup = Popup;


Vue.component('images-list', require("./components/ImagesList.vue"));
Vue.component('loader', require("./components/Loader.vue"));
Vue.component('form-ajax', require("./components/FormAjax.vue"));

// import RevySelect from './components/RevySelect.vue'
// window.RevySelect = RevySelect;