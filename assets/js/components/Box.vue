<template>
  <div
      class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600
">
    <div class="flex items-center justify-between mb-4">
      <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
        {{ title }}
      </h5>

      <span class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 cursor-pointer"
            @click="$emit('clickTitleButton')">
        {{ titleButtonContent }}
      </span>
    </div>

    <p class="font-normal text-gray-700 dark:text-gray-400">
      <slot name="content"></slot>
    </p>
  </div>
</template>
<script setup>
const props = defineProps([
  'title',
  'titleButtonContent'
])
defineEmits(['clickTitleButton'])
</script>