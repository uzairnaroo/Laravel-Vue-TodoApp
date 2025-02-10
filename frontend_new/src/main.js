import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import apiClient from './plugins/axios';
import './assets/tailwind.css';


const app = createApp(App);

app.config.globalProperties.$api = apiClient;

app.use(store);
app.use(router);
app.mount('#app');
