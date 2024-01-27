import axios from "axios";
import Routing from "fos-router";

export default {
  getMyShips() {
    return axios.get(Routing.generate('api_my_ships'))
    .then(resp => resp.data)
  },
  dockShip(data) {
    return axios.post(Routing.generate('api_dock_ship'), data)
    .then(resp => resp.data)
  },
  orbitShip(data) {
    return axios.post(Routing.generate('api_orbit_ship'), data)
    .then(resp => resp.data)
  },
  extract(data) {
    return axios.post(Routing.generate('api_ship_extract'), data)
    .then(resp => resp.data)
  },
  sell(data) {
    return axios.post(Routing.generate('api_ship_sell'), data)
    .then(resp => resp.data)
  },
  buyShip({shipType, waypointSymbol}) {
    return axios.post(Routing.generate('api_buy_ship'), {shipType, waypointSymbol})
    .then(resp => resp.data)
  },
  navigate({shipSymbol, waypointSymbol}) {
    return axios.post(Routing.generate('api_navigate_ship'), {shipSymbol, waypointSymbol})
    .then(resp => resp.data)
  }
}