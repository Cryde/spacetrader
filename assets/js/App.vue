<template>
  <div class="container mx-auto pt-6">
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