import axios from "axios";
import Routing from "fos-router";

export default {
  getMyContracts() {
    return axios.get(Routing.generate('api_my_contracts'))
    .then(resp => resp.data)
  }
}