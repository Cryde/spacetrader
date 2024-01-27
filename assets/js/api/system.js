import axios from "axios";
import Routing from "fos-router";

export default {
  getWaypointBySystem(systemSymbol, filters = {}) {
    return axios.get(Routing.generate('api_waypoints_collection', {systemSymbol, ...filters}))
    .then(resp => resp.data)
  },
  getShipyard(systemSymbol, waypointSymbol) {
    return axios.get(Routing.generate('api_shipyard_get', {systemSymbol, waypointSymbol}))
    .then(resp => resp.data)
  }
}