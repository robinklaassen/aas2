require('./bootstrap');
import Vue, {createApp} from "vue";
import makeTableSortable from "./helpers/tableSortable.js";

(window as any).Vue = Vue;
(window as any).makeTableSortable = makeTableSortable;


const app = createApp({})


app.component('flash-message', require('./components/FlashMessage.vue').default);
app.component('declarations-form', require('./components/declarations/DeclarationsForm.vue').default);
app.component('declaration-input-row', require('./components/declarations/DeclarationInputRow.vue').default);
app.component('file-dropzone', require('./components/FileDropzone.vue').default);
app.component('roles-table', require('./components/RolesTable/RolesTable.vue').default);
app.component('errors', require('./components/Errors.vue').default);
app.component('camp-thermometer-bar', require('./components/CampThermometer/CampThermometerBar.vue').default);
app.component('camp-year-map', require('./components/CampYearMap.vue').default);

app.mount('#vue-root');