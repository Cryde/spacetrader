import axios from "axios";
import Routing from "fos-router";

export default {
  getMyAgent() {
    return axios.get(Routing.generate('api_my_agent'))
    .then(resp => resp.data)
  }
}