<template>

  <div class="relative flex flex-col justify-center mt-20 overflow-hidden">
    <div class="w-full p-6 m-auto rounded-md lg:max-w-lg ">

      <div class="p-8">
        <div class="max-w-md mx-auto">
          <div class="mb-4 flex space-x-4 p-2 bg-slate-900 rounded-lg shadow-md">
            <button @click="openTab = 1" :class="{ 'bg-primary text-white': openTab === 1 }"
                    class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">
              Login
            </button>
            <button @click="openTab = 2" :class="{ 'bg-primary text-white': openTab === 2 }"
                    class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">
              Register
            </button>
          </div>

          <login
              v-show="openTab === 1"
              class="transition-all duration-300 pt-4 pb-4 px-1"
          />
          <register
              v-show="openTab === 2"
              class="transition-all duration-300 pb-4 pt-4 px-1"/>
        </div>
      </div>
    </div>
  </div>

</template>
<script setup>

import {ref} from "vue";
import Login from "./Login.vue";
import Register from "./Register.vue";

const openTab = ref(1);
</script>