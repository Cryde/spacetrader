<template>
  <div>

    <div role="alert" class="alert mb-10">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      <span>If you already have a token just fill the token</span>
    </div>


    <div role="alert" class="alert alert-error mt-4 mb-10" v-if="errors.length">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>

      <span v-for="error in errors" class="block">{{ error }}</span>
    </div>

    <div class="space-y-4">
      <div>
        <input type="text"
               :disabled="isUsernameDisabled"
               @input="(val) => (usernameRef = usernameRef.toUpperCase())"
               v-model="usernameRef"
               placeholder="Username" class="w-full input input-bordered input-primary"/>
      </div>
      <div>
        <input type="password" v-model="passwordRef" placeholder="Enter a password here"
               class="w-full input input-bordered input-primary"/>
      </div>
      <div>
        <input type="text" disabled placeholder="Faction" value="COSMIC"
               class="w-full input input-bordered input-primary"/>
      </div>
      <div>
        <input type="text"
               v-model="tokenRef"
               :disabled="isTokenDisabled"
               placeholder="Token" class="w-full input input-bordered input-primary"/>
      </div>
      <div>
        <button class="btn btn-primary btn-block" @click="register" :disabled="isSubmittingRef">
          Register
          <span class="loading loading-spinner" v-if="isSubmittingRef"></span>
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import {computed, ref} from "vue";
import agentApi from '../../api/agent';
import {emit} from "../../event/emitter";

const usernameRef = ref('');
const passwordRef = ref('');
const tokenRef = ref('');
const errors = ref([]);
const isSubmittingRef = ref(false);

const isTokenDisabled = computed(() => usernameRef.value.trim().length > 0);
const isUsernameDisabled = computed(() => tokenRef.value.trim().length > 0);

async function register() {
  errors.value = [];
  isSubmittingRef.value = true;
  if (usernameRef.value && isTokenDisabled) {
    try {
      const response = await agentApi.register({
        username: usernameRef.value,
        password: passwordRef.value
      });

      handleSuccess(response.token)
    } catch (e) {
      if (e.response.data.violations) {
        errors.value = e.response.data.violations.map((v) => v.message)
      } else if (e.response.data['hydra:description']) {
        errors.value = [e.response.data['hydra:description']];
      }
    }
  }

  if (tokenRef.value && isUsernameDisabled) {
    try {
      const response = await agentApi.register({
        token: tokenRef.value,
        password: passwordRef.value
      });
      handleSuccess(response.token)
    } catch (e) {
      if (e.response.data.violations) {
        errors.value = e.response.data.violations.map((v) => v.message)
      } else if (e.response.data['hydra:description']) {
        errors.value = [e.response.data['hydra:description']];
      }
    }
  }
  isSubmittingRef.value = false;
}

function handleSuccess(token) {
  localStorage.setItem("token", token);
  emit('register_success');
}

</script>