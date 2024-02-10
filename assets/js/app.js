import {createApp} from 'vue'
import { createPinia } from 'pinia'
import App from "./App.vue";
import '../styles/app.css';
import Routing from 'fos-router';
import router from './routes/router';

import routes from './routes.json';

Routing.setRoutingData(routes);

const pinia = createPinia()

createApp(App)
.use(router)
.use(pinia)
.mount('#app')
