<template>
  <div class="flex justify-end mb-3">
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
  </div>
</template>
<script setup>
import {formatMoney} from "../../helper/formatter";
import LibraryIcon from "../../components/Icons/LibraryIcon.vue";
import UserAloneIcon from "../../components/Icons/UserAloneIcon.vue";
import DollarIcon from "../../components/Icons/DollarIcon.vue";
import GroupIcon from "../../components/Icons/GroupIcon.vue";
import {onMounted, ref} from "vue";
import apiAgent from "../../api/agent";
import {on} from "../../event/emitter";

const myAgent = ref({});
const isAgentLoading = ref(true);

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
