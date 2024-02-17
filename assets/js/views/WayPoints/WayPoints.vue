<template>
  <div class="flex flex-row gap-x-8 gap-y-4">
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
              <option v-for="type in getWaypointTypes()" :value="type.key">{{type.label}}</option>
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
                  <button class="btn btn-xs" @click="openNavModal(waypoint.symbol)">
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

                  <button class="btn btn-xs" v-if="hasMarket(waypoint)" @click="loadMarketInfo(waypoint)">
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
          <span class="badge badge-primary mr-1 mb-1" v-for="shipType in shipyard.shipTypes">{{ shipType.type }}</span>

          <ul role="list" class="divide-y divide-gray-700">
            <li class="flex justify-between gap-x-6 py-5"
                v-if="shipyard.ships"
                v-for="ship in shipyard.ships">
              <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                  <p class="text-sm font-semibold text-white">
                    {{ ship.name }} <span class="font-light text-xs text-gray-400 ml-3">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="w-3 h-3 inline">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
</svg> {{ formatMoney(ship.purchasePrice) }}
                  </span>
                  </p>
                  <div class="mt-1 text-xs leading-5 text-gray-300">
                    {{ ship.description }}<br/>

                    <div v-if="ship.modules.length" class="mt-3">
                      Modules :
                      <div class="tooltip" :data-tip="formatModuleDescription(module)" v-for="module in ship.modules">
                        <span class="badge badge-primary mr-1 mb-1">{{ module.symbol }}</span>
                      </div>
                    </div>

                    <div v-if="ship.mounts.length" class="mt-3">
                      Mounts :
                      <div class="tooltip" :data-tip="formatMountDescription(mount)" v-for="mount in ship.mounts">
                        <span class="badge badge-primary mr-1 mb-1">{{ mount.symbol }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="text-sm leading-6 text-white">
                  <button class="btn btn-sm" @click="buyShip(shipyard.symbol, ship)" :disabled="isBuying">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 h-5 ">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                    </svg>
                    Buy
                  </button>
                </p>
                <p class="text-sm leading-5 text-white mt-2"></p>
              </div>
            </li>
          </ul>
        </template>
      </Box>
      <Box title="Market info" v-if="market && !isLoadingInfo">
        <template #content>
          <h3 class="font">{{ market.symbol }}</h3>

          <ul role="list" class="divide-y divide-gray-700">
            <li class="flex justify-between gap-x-6 py-5" v-if="market.exports.length">
              <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                  <p class="text-sm font-semibold text-white">Exports</p>
                  <div class="mt-1 text-xs leading-5 text-gray-300">
                    Exports are goods produced at the waypoint, and typically have a lower purchase price than import goods.<br/>

                    <div class="tooltip" :data-tip="exp.description" v-for="exp in market.exports">
                      <span class="badge badge-primary mr-1 mb-1">{{ exp.symbol }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="text-sm leading-6 text-white"></p>
                <p class="text-sm leading-5 text-white mt-2"></p>
              </div>
            </li>

            <li class="flex justify-between gap-x-6 py-5" v-if="market.imports.length">
              <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                  <p class="text-sm font-semibold text-white">Imports</p>
                  <div class="mt-1 text-xs leading-5 text-gray-300">
                    Import goods are consumed at the waypoint, and typically have a higher sell price.<br/>
                    <div class="tooltip" :data-tip="imp.description" v-for="imp in market.imports">
                      <span class="badge badge-primary mr-1 mb-1">{{ imp.symbol }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="text-sm leading-6 text-white"></p>
                <p class="text-sm leading-5 text-white mt-2"></p>
              </div>
            </li>

            <li class="flex justify-between gap-x-6 py-5" v-if="market.exchange.length">
              <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                  <p class="text-sm font-semibold text-white">Exchange</p>
                  <div class="mt-1 text-xs leading-5 text-gray-300">
                    Another type of market listing includes exchange goods. These goods are neither consumed nor produced at the waypoint. Instead, they are traded strictly among agents.<br/>
                    <div class="tooltip" :data-tip="ex.description" v-for="ex in market.exchange">
                      <span class="badge badge-primary mr-1 mb-1">{{ ex.symbol }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="text-sm leading-6 text-white"></p>
                <p class="text-sm leading-5 text-white mt-2"></p>
              </div>
            </li>
          </ul>

        </template>
      </Box>
    </div>
  </div>
  <navigation-modal ref="navModal" :waypoint-symbol="navWaypointSymbol"/>
</template>
<script setup>
import Box from "../../components/Box.vue";
import {onMounted, ref} from "vue";
import api from '../../api/system';
import apiShip from '../../api/ship';
import {formatMoney} from "../../helper/formatter";
import {emit} from "../../event/emitter";
import NavigationModal from "../Modal/NavigationModal.vue";
import {getWaypointTypes} from "../../helper/value-list";

const waypoints = ref(null);
const systemSymbolRef = ref(null);
const traitRef = ref(null);
const typeRef = ref(null);
const isLoading = ref(false);
const isLoadingInfo = ref(false);
const shipyard = ref(null);
const market = ref(null);
const isBuying = ref(false);
const navModal = ref(null);
const navWaypointSymbol = ref('');


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
  shipyard.value = null
  market.value = null
  const results = await api.getWaypointBySystem(systemSymbol, filters);
  waypoints.value = results['hydra:member'];
}

async function loadShipyardInfo(waypoint) {
  market.value = null;
  isLoadingInfo.value = true;
  shipyard.value = await api.getShipyard(waypoint.systemSymbol, waypoint.symbol);
  isLoadingInfo.value = false;
}

async function loadMarketInfo(waypoint) {
  shipyard.value = null
  isLoadingInfo.value = true;
  market.value = await api.getMarket(waypoint.systemSymbol, waypoint.symbol);
  isLoadingInfo.value = false;
}


function openNavModal(waypointSymbol) {
  navWaypointSymbol.value = waypointSymbol;
  navModal.value.$el.showModal();
}

function hasShipyard(waypoint) {
  return waypoint.traits.find((trait) => trait.symbol === 'SHIPYARD') !== undefined;
}

function hasMarket(waypoint) {
  return waypoint.traits.find((trait) => trait.symbol === 'MARKETPLACE') !== undefined;
}

function formatModuleDescription(module) {
  let str = '';
  if (module.capacity) {
    str += 'Capacity: ' + module.capacity;
  }

  return (str + ' ' + module.description).trim();
}

function formatMountDescription(mount) {
  let str = mount.description;
  if (mount.deposits && mount.deposits.length) {
    str += ' [' + mount.deposits.join(', ') + ']';
  }

  return str;
}

async function buyShip(waypointSymbol, ship) {
  isBuying.value = true;
  const result = await apiShip.buyShip({shipType: ship.type, waypointSymbol});
  isBuying.value = false;
  emit('toast', {type: 'success', message: 'Congrats! Ship has been buy !'})
  emit('ship_bought')
}
</script>