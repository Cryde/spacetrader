<template>
  <div class="flex flex-row gap-x-8 gap-y-4">
    <div class="basis-2/5">
      <my-contracts/>
    </div>
    <div class="basis-3/5">
      <my-chips/>
    </div>
  </div>
</template>
<script setup>
import MyChips from "./MyChips.vue";
import MyContracts from "./MyContracts.vue";
</script>