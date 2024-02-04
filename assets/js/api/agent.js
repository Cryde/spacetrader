import axios from "axios";
import Routing from "fos-router";

export default {
  getMyAgent() {
    return axios.get(Routing.generate('api_my_agent'))
    .then(resp => resp.data)
  },
  register({username = '', faction = 'COSMIC', token = '', password}) {
    return axios.post(Routing.generate('api_register'), {username, faction, token, password}, {
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json',
      }
    })
    .then(resp => resp.data)
  }
}