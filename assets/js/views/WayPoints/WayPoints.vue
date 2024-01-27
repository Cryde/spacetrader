<template>
  <div class="basis-3/5">
    <Box title="Waypoints">
      <template #content>
        <div class="join">
          <div>
            <div>
              <input class="input input-bordered join-item" placeholder="Search for system symbol"
                     ref="systemSymbolRef"/>
            </div>
          </div>
          <select class="select select-bordered join-item" ref="traitRef">
            <option disabled selected value="">Traits</option>
            <option value="">---</option>
            <option value="SHIPYARD">Shipyard</option>
            <option value="MARKETPLACE">Marketplace</option>
          </select>
          <select class="select select-bordered join-item" ref="typeRef">
            <option disabled selected value="">Type</option>
            <option value="">---</option>
            <option value="ENGINEERED_ASTEROID">Engineered Asteroid</option>
            <option value="ASTEROID">Asteroid</option>
            <option value="ORBITAL_STATION">Orbital Station</option>
          </select>
          <div class="indicator">
            <button class="btn join-item" @click="onSearch">Search</button>
          </div>
        </div>

        <span class="loading loading-ring loading-md" v-if="isLoading"></span>
        <ul role="list" class="divide-y divide-gray-700" v-else>
          <li class="flex justify-between gap-x-6 py-5"
              v-if="waypoints"
              v-for="waypoint in waypoints">
            <div class="flex min-w-0 gap-x-4">
              <div class="min-w-0 flex-auto">
                <p class="text-sm font-semibold leading-6 text-white">
                  {{ waypoint.type }}
                  <span class="font-light text-xs text-gray-400 ml-3">{{ waypoint.symbol }}</span>
                </p>
                <div class="mt-1 text-xs leading-5 text-gray-300">
                  <div class="tooltip" :data-tip="trait.description" v-for="trait in waypoint.traits">
                    <span class="badge badge-primary mr-1 mb-1">{{ trait.name }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
              <p class="text-sm leading-6 text-white">
                <button class="btn btn-xs">
                  Navigate there
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                  </svg>
                </button>
              </p>
              <p class="text-sm leading-5 text-white mt-2">
                <button class="btn btn-xs mr-2" v-if="hasShipyard(waypoint)" @click="loadShipyardInfo(waypoint)">
                  Shipyard info
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                  </svg>
                </button>

                <button class="btn btn-xs" v-if="hasMarket(waypoint)">
                  Market info
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                  </svg>
                </button>
              </p>
            </div>
          </li>
        </ul>
      </template>
    </Box>
  </div>

  <div class="basis-2/5">
    <span class="loading loading-ring loading-md" v-if="isLoadingInfo"></span>
    <Box title="Shipyard info" v-if="shipyard && !isLoadingInfo">
      <template #content>

        <h3 class="font">{{ shipyard.symbol }}</h3>
        {{ shipyard }}
      </template>
    </Box>
  </div>
</template>
<script setup>
import Box from "../../components/Box.vue";
import {onMounted, ref} from "vue";
import api from '../../api/system';

const waypoints = ref(null);
const systemSymbolRef = ref(null);
const traitRef = ref(null);
const typeRef = ref(null);
const isLoading = ref(false);
const isLoadingInfo = ref(false);
const shipyard = ref(null);


onMounted(async () => {
})

async function onSearch(e) {
  const systemSymbol = systemSymbolRef.value.value.trim();
  if (!systemSymbol) {
    return;
  }

  const type = typeRef.value.value || null;
  const traits = traitRef.value.value || null;

  isLoading.value = true;
  await loadWaypoint(systemSymbol, {type, traits});
  isLoading.value = false;
}

async function loadWaypoint(systemSymbol, filters) {
  const results = await api.getWaypointBySystem(systemSymbol, filters);
  waypoints.value = results['hydra:member'];
}

async function loadShipyardInfo(waypoint) {
  isLoadingInfo.value = true;
  shipyard.value = await api.getShipyard(waypoint.systemSymbol, waypoint.symbol);
  isLoadingInfo.value = false;
}

function hasShipyard(waypoint) {
  return waypoint.traits.find((trait) => trait.symbol === 'SHIPYARD') !== undefined;
}

function hasMarket(waypoint) {
  return waypoint.traits.find((trait) => trait.symbol === 'MARKETPLACE') !== undefined;
}
</script>