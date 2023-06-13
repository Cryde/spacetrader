<template>
  <Box title="My Ships" title-button-content="Refresh" @click-title-button="reload">
    <template #content>


      <div v-for="ship in ships">
        {{ ship.symbol }}
        <span
            class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
          status:{{ ship.nav.status }}
        </span>
        <span
            class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
          role:{{ ship.registration.role }}
        </span>

        <div v-if="ship.cargo.capacity > 0" class="mt-2 mb-2">
          <div class="flex justify-between mb-1">
            <span class="text-base font-medium text-blue-700 dark:text-white">Capacity</span>
            <span
                class="text-sm font-medium text-blue-700 dark:text-white">{{ getPercentFull(ship.cargo.capacity, ship.cargo.units) }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-blue-600 h-2.5 rounded-full"
                 :style="`width: ${getPercentFull(ship.cargo.capacity,ship.cargo.units)}%`"></div>
          </div>
        </div>

        <list-items v-if="ship.cargo.inventory.length > 0">
          <list-item
              v-for="inventory in ship.cargo.inventory"
              :left-text="inventory.symbol" :right-text="inventory.units"/>
        </list-items>

        <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
      </div>

    </template>
  </Box>
</template>
<script setup>
import Box from "../components/Box.vue";
import api from "../api/ship";
import {onMounted, ref} from "vue";
import ListItems from "../components/ListItems.vue";
import ListItem from "../components/ListItem.vue";

let ships = ref([]);

onMounted(async () => {
  ships.value = await getShips()
})

async function reload() {
  ships.value = await getShips()
}

async function getShips() {
  return (await api.getMyShips())['hydra:member'];
}

function getPercentFull(capacity, units) {
  return units / (capacity / 100);
  return 30;
}
</script>