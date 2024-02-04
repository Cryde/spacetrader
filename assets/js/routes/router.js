import {createRouter, createWebHashHistory} from 'vue-router'
import Dashboard from "../views/Dashboard/Dashboard.vue";
import WayPoints from "../views/WayPoints/WayPoints.vue";
import Settings from "../views/Settings/Settings.vue";

export default createRouter({
  history: createWebHashHistory(),
  routes: [
    {path: '/', name: 'dashboard', component: Dashboard},
    {path: '/waypoints', name: 'waypoints', component: WayPoints},
    {path: '/settings', name: 'settings', component: Settings},
  ],
  scrollBehavior(to, from, savedPosition) {
    // always scroll to top
    return { top: 0 }
  },
})