import axios from "axios";
import Routing from "fos-router";

export default {
  getMyContracts() {
    return axios.get(Routing.generate('api_my_contracts'))
    .then(resp => resp.data)
  },
  acceptContract(id) {
    return axios.post(Routing.generate('api_post_contract'), {id}, {
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json',
      }
    })
    .then(resp => resp.data)
  },
  fulfillContract(id) {
    return axios.post(Routing.generate('api_fulfill_contract'), {contractId: id}, {
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json',
      }
    })
    .then(resp => resp.data)
  }
}
