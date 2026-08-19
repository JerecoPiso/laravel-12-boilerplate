import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia'
import router from "./router";
import App from './App.vue'

// PRIMEVUE
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import { definePreset } from '@primeuix/themes';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';

// PRIMEVUE COMPONENTS
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import DatePicker from 'primevue/datepicker';
import Dialog from 'primevue/dialog';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';

const BluePreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{blue.50}',
            100: '{blue.100}',
            200: '{blue.200}',
            300: '{blue.300}',
            400: '{blue.400}',
            500: '{blue.500}',
            600: '{blue.600}',
            700: '{blue.700}',
            800: '{blue.800}',
            900: '{blue.900}',
            950: '{blue.950}'
        }
    }
});

const app = createApp(App);
const pinia = createPinia()
app.use(PrimeVue, {
    theme: {
        preset: BluePreset
    }
});
app.use(pinia);
app.use(ConfirmationService);
app.use(ToastService);

app.component('Button', Button)
app.component('Column', Column)
app.component('DataTable', DataTable)
app.component("DatePicker", DatePicker)
app.component('Dialog', Dialog)
app.component('FloatLabel', FloatLabel)
app.component('InputText', InputText)
app.component('Select', Select)
app.component('Tag', Tag)
app.component('Toast', Toast)

app.use(router);
app.mount("#app");
