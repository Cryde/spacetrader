<template>
  <dialog id="navigation_modal" class="modal">
    <div class="modal-box">
      <h3 class="font-bold text-lg">Navigation</h3>
      <p class="py-4">
        <label class="form-control w-full max-w-xs">
          <div class="label">
            <span class="label-text">Which ship ?</span>
          </div>
          <select class="select select-bordered" v-model="selectedShip">
            <option disabled selected>---</option>
            <option v-for="ship in availableShips" :value="ship.symbol">{{ ship.symbol }} - {{
                ship.registration.role
              }}
            </option>
          </select>
        </label>

        {{selectWaypointSymbol}}
        <label class="form-control w-full max-w-xs mt-3">
          <div class="label">
            <span class="label-text">Waypoint symbol</span>
          </div>
          <input type="text"
                 disabled
                 :value="props.waypointSymbol" placeholder="Type here"
                 class="input input-bordered w-full max-w-xs"/>
        </label>
      </p>

      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button in form, it will close the modal -->
          <button class="btn btn-outline btn-error mr-2">Cancel</button>
          <button class="btn btn-outline btn-success" @click="navigate">Navigate</button>
        </form>
      </div>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>
</template>
<script setup>
import {computed, nextTick, onMounted, ref} from "vue";
import api from "../../api/ship";

const props = defineProps(['waypointSymbol'])
let ships = ref([]);
let selectedShip = ref(null);
let selectWaypointSymbol = ref('');

onMounted(async () => {
  ships.value = await getShips()
})

const availableShips = computed(() => {
  return ships.value.filter((ship) => ship.nav.status === 'IN_ORBIT');
})

async function getShips() {
  return (await api.getMyShips())['hydra:member'];
}

async function navigate() {
  console.log(selectWaypointSymbol.value)
  await api.navigate({
    shipSymbol: selectedShip.value,
    waypointSymbol: props.waypointSymbol
  });
}

</script>