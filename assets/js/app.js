import {createApp} from 'vue'
import App from "./App.vue";
import '../styles/app.css';
import Routing from 'fos-router';

import routes from './routes.json';

Routing.setRoutingData(routes);

createApp(App).mount('#app')