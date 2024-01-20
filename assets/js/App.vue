<template>
  <div class="container mx-auto pt-6">
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
        <li class="mr-2" role="presentation">
          <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Dashboard</button>
        </li>
        <li class="mr-2" role="presentation">
          <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
        </li>
      </ul>
    </div>

    <div id="myTabContent">
      <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
        <div class="flex flex-row gap-x-8 gap-y-4">
          <div class="basis-1/5">
            <my-agent/>
          </div>
          <div class="basis-2/5">
            <my-contracts/>
          </div>

          <div class="basis-2/5">
            <my-chips/>
          </div>
        </div>
      </div>
      <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        todo
      </div>
    </div>
  </div>
</template>
<script setup>
import MyAgent from "./views/MyAgent.vue";
import MyContracts from "./views/MyContracts.vue";
import MyChips from "./views/MyChips.vue";
import { initFlowbite } from 'flowbite'
import {onMounted} from "vue";

// initialize components based on data attribute selectors
onMounted(() => {
  initFlowbite();
})
</script>