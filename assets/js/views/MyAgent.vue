<template>
  <Box title="My Agent" title-button-content="Refresh" @click-title-button="reload">
    <template #content>
      <list-items>
        <list-item left-text="Credits" :right-text="myAgent.credits"/>
        <list-item left-text="Headquarters" :right-text="myAgent.headquarters"/>
        <list-item left-text="Symbol" :right-text="myAgent.symbol"/>
        <list-item left-text="Faction" :right-text="myAgent.startingFaction"/>
      </list-items>
    </template>
  </Box>
</template>

<script setup>
import {onMounted, ref} from "vue";
import Box from "../components/Box.vue";

import api from '../api/agent';
import ListItems from "../components/ListItems.vue";
import ListItem from "../components/ListItem.vue";

let myAgent = ref({});

onMounted(async () => {
  myAgent.value = await api.getMyAgent();
})

async function reload() {
  myAgent.value = await api.getMyAgent();
}

</script>