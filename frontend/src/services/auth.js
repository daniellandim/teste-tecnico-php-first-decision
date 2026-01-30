import api from "@/services/api.js";

export default {
  login(data) {
    return api.post('/login', data).then(res => res.data)
  }
}
