<template>
  <Box title="Settings">
    <template #content>
      <div>
        <span class="mr-3">Dark mode</span>
        <label class="swap swap-rotate">
          <input type="checkbox" class="theme-controller" value="dark" @change="onModeChange"/>
          <sun-icon class="w-4 h-4 swap-on fill-current"/>
          <moon-icon class="w-4 h-4 swap-off fill-current"/>
        </label>
      </div>
    </template>
  </Box>
</template>
<script setup>
import Box from "../../components/Box.vue";
import SunIcon from "../../components/Icons/SunIcon.vue";
import MoonIcon from "../../components/Icons/MoonIcon.vue";

function onModeChange(e) {
  if (localStorage.theme === 'dark') {
    localStorage.theme = 'light'
    document.documentElement.classList.remove('dark')
  } else {
    localStorage.theme = 'dark';
    document.documentElement.classList.add('dark')
  }
}
</script>