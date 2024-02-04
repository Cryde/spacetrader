import {createRouter, createWebHashHistory} from 'vue-router'
import Dashboard from "../views/Dashboard/Dashboard.vue";
import WayPoints from "../views/WayPoints/WayPoints.vue";

export default createRouter({
  history: createWebHashHistory(),
  routes: [
    {path: '/', name: 'dashboard', component: Dashboard},
    {path: '/waypoints', name: 'waypoints', component: WayPoints},
  ],
  scrollBehavior(to, from, savedPosition) {
    // always scroll to top
    return { top: 0 }
  },
})