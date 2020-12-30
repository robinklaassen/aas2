require('./bootstrap');
import Vue from "vue";
import makeTableSortable from "./helpers/tableSortable.js";

(window as any).Vue = Vue;
(window as any).makeTableSortable = makeTableSortable;

Vue.component('flash-message', require('./components/FlashMessage.vue').default);
Vue.component('declarations-form', require('./components/declarations/DeclarationsForm.vue').default);
Vue.component('declaration-input-row', require('./components/declarations/DeclarationInputRow.vue').default);
Vue.component('file-dropzone', require('./components/FileDropzone.vue').default);
Vue.component('errors', require('./components/Errors.vue').default);

const app = new Vue({
    el: '#vue-root',
});