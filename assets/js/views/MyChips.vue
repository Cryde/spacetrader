<template>
  <Box title="My Ships" title-button-content="Refresh" @click-title-button="reload">
    <template #content>


      <div v-for="ship in ships">
        <strong>{{ ship.symbol }}</strong>
        <span
            class="ml-3 bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ ship.nav.waypointSymbol }}</span>
        <span
            class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">status: {{ ship.nav.status }}</span>
        <span
            class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">role: {{ ship.registration.role }}</span>

        <div class="mt-2">
          <button
              v-if="ship.nav.status === 'IN_ORBIT'"
              @click="dockShip(ship.symbol)"
              type="button"
              class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            dock
          </button>

          <button
              v-if="ship.nav.status === 'DOCKED'"
              @click="orbitShip(ship.symbol)"
              type="button"
              class="mt-1 ml-2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            orbit
          </button>

          <button
              v-if="ship.nav.status === 'IN_ORBIT'"
              @click="extract(ship.symbol)"
              type="button"
              class="mt-1 ml-2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            extract
          </button>
        </div>

        <div v-if="ship.cargo.capacity > 0" class="mt-3 mb-2">
          <div class="flex justify-between mb-1">
            <span class="text-base font-medium text-blue-700 dark:text-white">Capacity</span>
            <span
                class="text-sm font-medium text-blue-700 dark:text-white">{{
                getPercentFull(ship.cargo.capacity, ship.cargo.units)
              }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-blue-600 h-2.5 rounded-full"
                 :style="`width: ${getPercentFull(ship.cargo.capacity,ship.cargo.units)}%`"></div>
          </div>
        </div>

        <div v-if="ship.cargo.inventory.length > 0" class="grid grid-cols-3 gap-4 mt-4">
          <div v-for="inventory in ship.cargo.inventory" class="text-sm">
            <span id="badge-dismiss-pink"
                  class="inline-flex items-center px-2 py-1 mr-2 text-sm font-medium text-pink-800 bg-pink-100 rounded dark:bg-pink-900 dark:text-pink-300">
                {{ inventory.symbol }}: {{ inventory.units }}
                <button type="button" v-if="ship.nav.status === 'DOCKED'"
                        @click="sell(ship.symbol, inventory.symbol, inventory.units)"
                        class="inline-flex items-center p-0.5 ml-2 text-sm text-pink-400 bg-transparent rounded-sm hover:bg-pink-200 hover:text-pink-900 dark:hover:bg-pink-800 dark:hover:text-pink-300"
                        data-dismiss-target="#badge-dismiss-pink" aria-label="Remove">
                    <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd"
                                                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                  clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Sell item</span>
                </button>
              </span>
          </div>
        </div>

        <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
      </div>

    </template>
  </Box>
</template>
<script setup>
import Box from "../components/Box.vue";
import api from "../api/ship";
import {onMounted, ref} from "vue";
import {on} from "../event/emitter";

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

on('ship_bought', async () => {
  reload();
});

async function dockShip(symbol) {
  await api.dockShip({symbol});
  reload();
}

async function orbitShip(symbol) {
  await api.orbitShip({symbol});
  reload();
}

async function sell(shipSymbol, unitSymbol, amount) {
  await api.sell({
    shipSymbol,
    unitSymbol,
    amount
  });
  reload();
}

async function extract(symbol) {

  try {
    await api.extract({symbol});
  } catch (e) {
    console.log(e.response);
  }
  reload();
}

function getPercentFull(capacity, units) {
  return units / (capacity / 100);
}
</script>