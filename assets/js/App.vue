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
        <div role="tablist" class="tabs tabs-bordered">
          <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Dashboard" checked/>
          <div role="tabpanel" class="tab-content pt-10 pb-10">
            <div class="flex flex-row gap-x-8 gap-y-4">
              <div class="basis-2/5">
                <my-contracts/>
              </div>
              <div class="basis-3/5">
                <my-chips/>
              </div>
            </div>
          </div>

          <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Waypoints"/>
          <div role="tabpanel" class="tab-content pt-10 pb-10">
            <div class="flex flex-row gap-x-8 gap-y-4">
              <way-points/>
            </div>
          </div>

          <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Navigation"/>
          <div role="tabpanel" class="tab-content p-10">
            Todo
          </div>

          <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Settings"/>
          <div role="tabpanel" class="tab-content p-10">todo</div>
        </div>

      </div>
    </template>
  </template>
  <toast/>
</template>
<script setup>
import MyContracts from "./views/MyContracts.vue";
import MyChips from "./views/MyChips.vue";
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