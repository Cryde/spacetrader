<template>
  <div class="toast toast-start">
    <div class="alert alert-success"
         v-for="item in successItems"
         :key="item.id"
         :ref="(el) => (itemsRefs[item.id] = el)">
      <span>{{ item.message }}</span>
    </div>
  </div>
</template>

<script setup>
import {on} from "../event/emitter";
import {nextTick, ref} from "vue";

const successItems = ref([]);
const itemsRefs = ref({});

on('toast', (e) => {
  if (e.type === 'success') {
    const id = random();
    successItems.value.push({id, message: e.message});
    nextTick(() => {
      handleHide(id, itemsRefs.value[id]);
    })
  }
})

function handleHide(id, item) {
  setTimeout(() => {
    item.classList.add('duration-700', 'opacity-0');
    setTimeout(() => handleRemove(id, item), 800);
  }, 2000);
}

function handleRemove(id) {
  successItems.value = successItems.value.filter(function (item) {
    return item.id !== id;
  });
}

function random() {
  return parseInt(Math.random() * 10000000, 10);
}
</script>