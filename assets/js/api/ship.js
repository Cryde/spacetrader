import axios from "axios";
import Routing from "fos-router";

export default {
  getMyShips() {
    return axios.get(Routing.generate('api_my_ships'))
    .then(resp => resp.data)
  }
}