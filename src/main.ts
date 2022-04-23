import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import './assets/scss/index.scss';

console.log("%cIf someone instructed you to copy and paste something here, it is a scam aiming to gain access to your account.", "color:red; font-size:30px");
createApp(App).use(store).use(router).mount('#danonek');