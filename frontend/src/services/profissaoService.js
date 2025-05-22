import api from './api';

export default {
  getProfissoes() {
    return api.get('/profissoes');
  }
};