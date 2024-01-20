import {createApp} from 'vue'
import App from "./App.vue";
import '../styles/app.css';
import Routing from 'fos-router';
//import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import routes from './routes.json';

Routing.setRoutingData(routes);

createApp(App).mount('#app')