import api from './api';

export default {
  getClientes() {
    return api.get('/clientes');
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
  }
};