import api from "@/services/api.js";

export default {
  async all(params) {
    return api.get('/products', {params}).then(res => res.data.data)
  },

  async show(id) {
    return api.get(`/products/${id}`)
  },

  async store(data) {
    return api.post('/products', data).then(res => res.data)
  },

  async update(id, payload) {
    return api.put(`/products/${id}`, payload)
  },

  async delete(id) {
    return api.delete(`/products/${id}`)
  }
}
