import './bootstrap';
import { createApp } from 'vue';
import PdfPreview from './components/PdfPreview.vue';

createApp({})
    .component('PdfPreview', PdfPreview)
    .mount('#app')
