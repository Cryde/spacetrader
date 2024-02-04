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
        <a class="btn btn-ghost text-xl">SpaceTrader</a>
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
          <div v-else>
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
            <span class="mr-3 hidden sm:inline-block  ">
                <group-icon class="w-4 h-4 inline-block align-sub text-white mr-1"/>
                <span class="text-sm select-all">{{ myAgent.startingFaction }}</span>
              </span>
          </div>
          <div class="ml-2 sm:ml-20 mr-2">
            <label class="swap swap-rotate">
              <!-- this hidden checkbox controls the state -->
              <input type="checkbox" class="theme-controller" value="dark" @change="onModeChange"/>
              <!-- sun icon -->
              <svg class="swap-on fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/>
              </svg>
              <!-- moon icon -->
              <svg class="swap-off fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/>
              </svg>
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