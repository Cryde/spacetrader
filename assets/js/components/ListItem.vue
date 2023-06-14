<template>
  <li class="py-3 sm:py-4">
    <div class="flex items-center space-x-4">
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ leftText }}</p>
        <p v-if="slots.content" class="text-sm text-gray-500 truncate dark:text-gray-400">
          <slot name="content"></slot>
        </p>
      </div>
      <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
        {{ rightText }}
      </div>
    </div>
  </li>
</template>

<script setup>
import {useSlots} from 'vue'

const slots = useSlots()
const props = defineProps([
  'leftText',
  'rightText'
])
</script>