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
            <li>
              <router-link :to="{ name: 'settings'}" exact-active-class="bg-navbar-active">
                Settings
              </router-link>
            </li>
            <li><a @click="logout">Logout</a></li>
          </ul>
        </div>
        <a class="btn hidden sm:inline-flex sm:ml-2 lg:ml-0 text-xl">
          <rocket-icon class="w-4 h-4 inline"/> SpaceTrader
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
          <li class="px-1 disabled"><a>Ships</a></li>
          <li class="px-1 disabled"><a>Contracts</a></li>
        </ul>
      </div>

      <div class="navbar-end hidden lg:flex">
        <div class="dropdown dropdown-end">
          <div tabindex="0" role="button" class="btn btn-ghost">
            <div class="w-10">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                   class="inline-block w-5 h-5 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
              </svg>
            </div>
          </div>
          <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
            <li>
              <router-link :to="{ name: 'settings'}" exact-active-class="bg-navbar-active">
                Settings
              </router-link>
            </li>
            <li><a @click="logout">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import RocketIcon from "./Icons/RocketIcon.vue";
import {emit} from "../event/emitter";

function logout() {
  emit('logout');
}

</script>