import {defineStore} from 'pinia'
import {computed, ref} from "vue";
import api from "../../api/contract";
import {emit} from "../../event/emitter";

export const useContractsStore = defineStore('contracts', () => {

  const contractsRef = ref([])
  const isLoading = ref(false);

  const contracts = computed(() => contractsRef.value);
  const isContractLoading = computed(() => isLoading.value);

  async function loadContracts() {
    isLoading.value = true;
    contractsRef.value = (await api.getMyContracts())['hydra:member'];
    isLoading.value = false;
  }

  async function acceptContract(id) {
    await api.acceptContract(id);
    await loadContracts()
  }

  async function fulfillContract(id) {
    await api.fulfillContract(id);
    emit('fulfill_contract');
    await loadContracts()
  }

  return {loadContracts, acceptContract, fulfillContract, isContractLoading, contracts};
});