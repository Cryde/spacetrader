<template>
  <Box title="My Contracts" title-button-content="Refresh" @click-title-button="reload">
    <template #content>
      <div v-for="contract in contracts">

        <div v-if="!contract.accepted" class="text-right mb-2">
          <button
              @click="acceptContract(contract.id)"
              type="button"
              :disabled="isLoading"
              class="mt-1 ml-2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Accept this contract
          </button>
        </div>

        <div class="text-right mb-2" v-if="!isFulfill(contract)">
          <button
              @click="fulfillContract(contract.id)"
              type="button"
              :disabled="isLoading"
              class="mt-1 ml-2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Complete this contract
          </button>
        </div>

        <strong>ID</strong> : {{ contract.id }} - <strong>Type</strong> : {{ contract.type }}<br/>
        <strong>Accepted</strong> : {{ contract.accepted ? 'Yes' : 'No' }} - <strong>Completed</strong> :
        {{ contract.fulfilled ? 'Yes' : 'No' }}<br/>
        <strong>Expiration</strong> : {{ contract.expiration }} <br/>

        <hr class="h-px my-3 bg-gray-200 border-0 dark:bg-gray-700">

        <strong>Deadline</strong> : {{ contract.terms.deadline }}<br/>
        <strong>Payment on accepted</strong> : {{ contract.terms.payment.onAccepted }}<br/>
        <strong>Payment on completed</strong> : {{ contract.terms.payment.onFulfilled }}<br/>

        <hr class="h-px my-3 bg-gray-200 border-0 dark:bg-gray-700">

        <div v-for="(deliver, key) in contract.terms.deliver">
          Deliver n°{{ key + 1 }}<br/>
          <strong>Trade symbol</strong> : {{ deliver.tradeSymbol }}<br/>
          <strong>Destination symbol</strong> : {{ deliver.destinationSymbol }}<br/>
          <strong>Unit required</strong> : {{ deliver.unitsRequired }}<br/>
          <strong>Unit completed</strong> : {{ deliver.unitsFulfilled }}<br/>
          <div class="flex justify-between mb-1">
            <span class="text-base font-medium text-blue-700 dark:text-white">Progression</span>
            <span
                class="text-sm font-medium text-blue-700 dark:text-white">
              {{
                formatPercent(getPercentFull(deliver.unitsRequired, deliver.unitsFulfilled))
              }}</span>
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
import Box from "../../components/Box.vue";

import api from '../../api/contract';
import {formatPercent} from "../../helper/formatter";
import {emit} from "../../event/emitter";

let contracts = ref([]);
let isLoading = ref(true);

onMounted(async () => {
  isLoading.value = true;
  contracts.value = await getContracts()
  isLoading.value = false;
})

async function reload() {
  isLoading.value = true;
  contracts.value = await getContracts()
  isLoading.value = false;
}

async function getContracts() {
  return (await api.getMyContracts())['hydra:member'];
}

async function acceptContract(id) {
  await api.acceptContract(id);
  await reload();
}

async function fulfillContract(id) {
  await api.fulfillContract(id);
  emit('fulfill_contract');
  await reload();
}

function isFulfill(contract) {

  for (const deliver of contract.terms.deliver) {
    if (deliver.unitsRequired !== deliver.unitsFulfilled) {
      return false;
    }
  }

  return contract.fulfilled;
}

function getPercentFull(total, current) {
  return current / (total / 100);
}
</script>