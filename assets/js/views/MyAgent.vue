<template>
  <Box class="max-w-sm" title="My Agent" title-button-content="Refresh" @click-title-button="reload">
    <template #content>
      <div class="flow-root">
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
          <li class="py-3 sm:py-4">
            <div class="flex items-center space-x-4">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">Credits</p>
              </div>
              <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                {{ myAgent.credits }}
              </div>
            </div>
          </li>
          <li class="py-3 sm:py-4">
            <div class="flex items-center space-x-4">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">Headquarters</p>
              </div>
              <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                {{ myAgent.headquarters }}
              </div>
            </div>
          </li>
          <li class="py-3 sm:py-4">
            <div class="flex items-center space-x-4">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">Symbol</p>
              </div>
              <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                {{ myAgent.symbol }}
              </div>
            </div>
          </li>
          <li class="py-3 sm:py-4">
            <div class="flex items-center space-x-4">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">Faction</p>
              </div>
              <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                {{ myAgent.startingFaction }}
              </div>
            </div>
          </li>
        </ul>
      </div>
    </template>
  </Box>
</template>

<script setup>
import {onMounted, ref} from "vue";
import Box from "../components/Box.vue";

import api from '../api/agent';

let myAgent = ref({});

onMounted(async () => {
  myAgent.value = await api.getMyAgent();
})

async function reload() {
  myAgent.value = await api.getMyAgent();
}

</script>