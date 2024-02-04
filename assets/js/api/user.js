import axios from "axios";
import Routing from "fos-router";

export default {
  accessCheck() {
    return axios.get(Routing.generate('api_access_check'))
    .then(resp => resp.data)
  },
  login(username, password) {
    return axios.post(Routing.generate('api_login'), {username, password})
    .then(resp => resp.data)
  }
}