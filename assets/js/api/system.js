import axios from "axios";
import Routing from "fos-router";

export default {
  getWaypointBySystem(systemSymbol, filters = {}) {
    return axios.get(Routing.generate('api_waypoints_collection', {systemSymbol, ...filters}))
    .then(resp => resp.data)
  },
}