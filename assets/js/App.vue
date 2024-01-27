<template>
  <nav-bar/>
  <div class="container mx-auto pt-6">
    <div role="tablist" class="tabs tabs-bordered">
      <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Dashboard" checked/>
      <div role="tabpanel" class="tab-content pt-10 pb-10">
        <div class="flex flex-row gap-x-8 gap-y-4">
          <div class="basis-1/5">
            <my-agent/>
          </div>
          <div class="basis-2/5">
            <my-contracts/>
          </div>
          <div class="basis-2/5">
            <my-chips/>
          </div>
        </div>
      </div>

      <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Waypoints"/>
      <div role="tabpanel" class="tab-content pt-10 pb-10">
        <div class="flex flex-row gap-x-8 gap-y-4">
            <way-points/>
        </div>
      </div>

      <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Navigation"/>
      <div role="tabpanel" class="tab-content p-10">
        Todo
      </div>

      <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Settings"/>
      <div role="tabpanel" class="tab-content p-10">todo</div>
    </div>

  </div>
  <toast/>
</template>
<script setup>
import MyAgent from "./views/MyAgent.vue";
import MyContracts from "./views/MyContracts.vue";
import MyChips from "./views/MyChips.vue";
import WayPoints from "./views/WayPoints/WayPoints.vue";
import Toast from "./components/Toast.vue";
import NavBar from "./components/NavBar.vue";

</script>