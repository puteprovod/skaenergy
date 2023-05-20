import './bootstrap';
import '../css/app.css';
import '../css/fonts/stylesheet.css';
import Alpine from 'alpinejs';
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from "axios";



window.Alpine = Alpine;
Alpine.start();
const app = createApp(App)

app.use(router)
app.config.globalProperties.axios = axios
app.mount('#app')
