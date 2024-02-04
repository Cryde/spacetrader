<template>

  <div v-if="hasAppAccessLoading" class="text-center pt-10">
    <span class="loading loading-ring loading-lg"></span>
  </div>
  <template v-else>
    <template v-if="!hasAppAccess">
      <security-entry/>
    </template>
    <template v-else>
      <nav-bar/>
      <div class="container mx-auto pt-6 pb-6">
        <summary-agent/>
        <router-view></router-view>
      </div>
    </template>
  </template>
  <toast/>
</template>
<script setup>
import Toast from "./components/Toast.vue";
import NavBar from "./components/NavBar.vue";
import {onMounted, ref} from "vue";
import userApi from "./api/user";
import SecurityEntry from "./views/Security/SecurityEntry.vue";
import axios from "axios";
import {on} from "./event/emitter";
import SummaryAgent from "./views/Agent/SummaryAgent.vue";

const hasAppAccess = ref(false);
const hasAppAccessLoading = ref(true);

if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark')
} else {
  document.documentElement.classList.remove('dark')
}

onMounted(async () => {
  await checkIsAuth();

  on('logout', () => {
    localStorage.removeItem("token");
    checkIsAuth();
  });
  on('login_success', checkIsAuth);
  on('register_success', checkIsAuth);
});


async function checkIsAuth() {
  axios.interceptors.request.use(function (config) {
    const token = getToken();
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  });

  try {
    hasAppAccessLoading.value = true;
    await userApi.accessCheck();
    hasAppAccess.value = true;
  } catch (e) {
    if (e.response.status === 401) {
      hasAppAccess.value = false;
    }
  }

  hasAppAccessLoading.value = false;
}

function getToken() {
  return localStorage.getItem("token");
}

</script>