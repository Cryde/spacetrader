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
      <div class="container mx-auto pt-6">
        <router-view></router-view>
      </div>
    </template>
  </template>
  <toast/>
</template>
<script setup>
import MyContracts from "./views/Dashboard/MyContracts.vue";
import MyChips from "./views/Dashboard/MyChips.vue";
import WayPoints from "./views/WayPoints/WayPoints.vue";
import Toast from "./components/Toast.vue";
import NavBar from "./components/NavBar.vue";
import {onMounted, ref} from "vue";
import userApi from "./api/user";
import SecurityEntry from "./views/Security/SecurityEntry.vue";
import axios from "axios";
import {on} from "./event/emitter";

const hasAppAccess = ref(false);
const hasAppAccessLoading = ref(true);

onMounted(async () => {
  await checkIsAuth();

  on('login_success', checkIsAuth);
  on('register_success', checkIsAuth);
});


async function checkIsAuth() {
  const token = localStorage.getItem("token");
  if (token) {
    axios.interceptors.request.use(function (config) {
      config.headers.Authorization = `Bearer ${token}`;

      return config;
    });
  }

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


</script>