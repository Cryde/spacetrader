<template>
  <Box title="My Contracts" title-button-content="Refresh" @click-title-button="reload">
    <template #content>
      <div v-for="contract in contracts">
        <strong>ID</strong> : {{ contract.id }}  - <strong>Type</strong> : {{ contract.type }}<br/>
        <strong>Accepted</strong> : {{ contract.accepted ? 'Yes' : 'No' }}  - <strong>Completed</strong> : {{ contract.fulfilled ? 'Yes' : 'No' }}<br/>
        <strong>Expiration</strong> : {{ contract.expiration }} <br/>

        <hr class="h-px my-3 bg-gray-200 border-0 dark:bg-gray-700">

        <strong>Deadline</strong> : {{ contract.terms.deadline }}<br/>
        <strong>Payment on accepted</strong> : {{ contract.terms.payment.onAccepted }}<br/>
        <strong>Payment on completed</strong> : {{ contract.terms.payment.onFulfilled }}<br/>

        <hr class="h-px my-3 bg-gray-200 border-0 dark:bg-gray-700">

        <div v-for="(deliver, key) in contract.terms.deliver">
          Deliver n°{{key+1}}<br/>
          <strong>Trade symbol</strong> : {{ deliver.tradeSymbol }}<br/>
          <strong>Destination symbol</strong> : {{ deliver.destinationSymbol }}<br/>
          <strong>Unit required</strong> : {{ deliver.unitsRequired }}<br/>
          <strong>Unit completed</strong> : {{ deliver.unitsFulfilled }}<br/>
          <div class="flex justify-between mb-1">
            <span class="text-base font-medium text-blue-700 dark:text-white">Progression</span>
            <span
                class="text-sm font-medium text-blue-700 dark:text-white">{{
                getPercentFull(deliver.unitsRequired, deliver.unitsFulfilled)
              }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-blue-600 h-2.5 rounded-full"
                 :style="`width: ${getPercentFull(deliver.unitsRequired, deliver.unitsFulfilled)}%`"></div>
          </div>
        </div>
      </div>
    </template>
  </Box>
</template>
<script setup>
import {onMounted, ref} from "vue";
import Box from "../components/Box.vue";

import api from '../api/contract';

let contracts = ref([]);

onMounted(async () => {
  contracts.value = await getContracts()
})

async function reload() {
  contracts.value = await getContracts()
}

async function getContracts() {
  return (await api.getMyContracts())['hydra:member'];
}

function getPercentFull(total, current) {
  return current / (total / 100);
}
</script>