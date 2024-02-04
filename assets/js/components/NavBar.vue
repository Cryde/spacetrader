<template>
  <div class="navbar bg-neutral z-50 fixed top-0">
    <div class="container mx-auto">
      <div class="navbar-start">
        <div class="dropdown">
          <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
            </svg>
          </div>
          <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
            <li>
              <router-link :to="{ name: 'dashboard'}" exact-active-class="bg-navbar-active">
                Dashboard
              </router-link>
            </li>
            <li>
              <router-link :to="{ name: 'waypoints'}" exact-active-class="bg-navbar-active">
                Waypoints
              </router-link>
            </li>
          </ul>
        </div>
        <a class="btn btn-ghost text-xl">
          <rocket-icon class="w-4 h-4"/>
          SpaceTrader
        </a>
      </div>

      <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal">
          <li class="px-1">
            <router-link :to="{ name: 'dashboard'}" exact-active-class="bg-navbar-active">
              Dashboard
            </router-link>
          </li>
          <li class="px-1">
            <router-link :to="{ name: 'waypoints'}" exact-active-class="bg-navbar-active">
              Waypoints
            </router-link>
          </li>
          <li class="px-1 disabled"><a>Navigation</a></li>
          <li class="px-1 disabled"><a>Settings</a></li>
        </ul>
      </div>

      <div class="navbar-end">
        <div class="flex justify-end">
          <div class="loading loading-ring loading-sm" v-if="isAgentLoading"></div>
          <div v-else class="hidden md:block">
              <span class="mr-3 hidden sm:inline-block">
                <user-alone-icon class="w-4 h-4 inline-block align-sub text-white mr-1"/>
                <span class="text-sm select-all">{{ myAgent.symbol }}</span>
              </span>
            <span class="mr-3">
                <library-icon class="w-4 h-4 inline-block align-sub text-white mr-1"/>
                <span class="text-sm select-all">{{ myAgent.headquarters }}</span>
              </span>
            <span class="mr-3">
                <dollar-icon class="w-4 h-4 inline-block align-sub text-white mr-1"/>
                <span class="text-sm">{{ formatMoney(myAgent.credits) }}</span>
              </span>
            <span class="mr-3 hidden sm:inline-block">
                <group-icon class="w-4 h-4 inline-block align-sub text-white mr-1"/>
                <span class="text-sm select-all">{{ myAgent.startingFaction }}</span>
              </span>
          </div>
          <div class="ml-2 sm:ml-20 mr-2">
            <label class="swap swap-rotate">
              <!-- this hidden checkbox controls the state -->
              <input type="checkbox" class="theme-controller" value="dark" @change="onModeChange"/>
              <sun-icon class="w-4 h-4 swap-on fill-current"/>
              <moon-icon class="w-4 h-4 swap-off fill-current"/>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import {onMounted, ref} from "vue";
import UserAloneIcon from "./Icons/UserAloneIcon.vue";
import DollarIcon from "./Icons/DollarIcon.vue";
import GroupIcon from "./Icons/GroupIcon.vue";
import LibraryIcon from "./Icons/LibraryIcon.vue";
import apiAgent from '../api/agent';
import {formatMoney} from "../helper/formatter";
import {on} from "../event/emitter";
import RocketIcon from "./Icons/RocketIcon.vue";
import SunIcon from "./Icons/SunIcon.vue";
import MoonIcon from "./Icons/MoonIcon.vue";

let myAgent = ref({});
let isAgentLoading = ref(true);

if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark')
} else {
  document.documentElement.classList.remove('dark')
}

function onModeChange(e) {
  if (localStorage.theme === 'dark') {
    localStorage.theme = 'light'
    document.documentElement.classList.remove('dark')
  } else {
    localStorage.theme = 'dark';
    document.documentElement.classList.add('dark')
  }
}


onMounted(async () => {
  isAgentLoading.value = true;
  myAgent.value = await apiAgent.getMyAgent();
  isAgentLoading.value = false;
})

on('ship_bought', async () => {
  myAgent.value = await apiAgent.getMyAgent();
});

on('fulfill_contract', async () => {
  myAgent.value = await apiAgent.getMyAgent();
});

</script>