<template>
  <div>
    <div>
      <input type="text"
             placeholder="Username"
             v-model="usernameRef"
             @input="(val) => (usernameRef = usernameRef.toUpperCase())"
             class="w-full input input-bordered input-primary"/>
    </div>

    <div class="mt-2">
      <input type="password" v-model="passwordRef" placeholder="Enter your password here"
             class="w-full input input-bordered input-primary"/>
    </div>

    <div class="mt-4">
      <button class="btn btn-primary btn-block" :disabled="!canLogin" @click="login">Login</button>
    </div>
  </div>
</template>
<script setup>
import {computed, ref} from "vue";
import apiUser from '../../api/user';
import {emit} from "../../event/emitter";

const usernameRef = ref('');
const passwordRef = ref('');

const canLogin = computed(() => usernameRef.value.trim().length > 0 && passwordRef.value.trim().length > 0);

async function login() {
  const response = await apiUser.login(usernameRef.value, passwordRef.value);
  localStorage.setItem("token", response.token);
  emit('login_success');
}
</script>