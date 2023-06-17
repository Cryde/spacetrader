<template>
  <Box title="My Ships" title-button-content="Refresh" @click-title-button="reload">
    <template #content>


      <div v-for="ship in ships">
        <strong>{{ ship.symbol }}</strong>
        <button
            v-if="ship.nav.status === 'IN_ORBIT'"
            @click="dockShip(ship.symbol)"
            type="button"
            class="mt-1 ml-3 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
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

        <div class="mt-2">
        <span
            class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
          status:{{ ship.nav.status }}
        </span>
          <span
              class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
          role:{{ ship.registration.role }}
        </span>
        </div>

        <div v-if="ship.cargo.capacity > 0" class="mt-2 mb-2">
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

        <list-items v-if="ship.cargo.inventory.length > 0">
          <list-item
              v-for="inventory in ship.cargo.inventory"
              :left-text="inventory.symbol" :right-text="inventory.units">
            <template #content>
              <button type="button" :disabled="ship.nav.status !== 'DOCKED'"
                      @click="sell(ship.symbol, inventory.symbol, inventory.units)"
                      class="mt-1 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                sell
              </button>
            </template>

          </list-item>
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