import api from './api';

export default {
  getClientes(params = {}) {
    return api.get('/clientes', { params });
  },

  getCliente(id) {
    return api.get(`/clientes/${id}`);
  },

  createCliente(cliente) {
    return api.post('/clientes', cliente);
  },

  updateCliente(id, cliente) {
    return api.put(`/clientes/${id}`, cliente);
  },

  deleteCliente(id) {
    return api.delete(`/clientes/${id}`);
  },
  getStats() {
    return api.get('/clientes/stats');
  }
};