import clienteService from '@/services/clienteService';

export default {
  namespaced: true,

  state: {
    clientes: [],
    cliente : null,
    loading : false,
    error   : null,
    stats   : {
      total_clientes : 0,
      clientes_ativos: 0,
      novos_mes      : 0
    },

    formData: {
      nome           : '',
      data_nascimento: '',
      tipo_pessoa    : 'fisica',
      documento      : '',
      email          : '',
      telefone       : '',
      endereco       : '',
      numero         : '',
      bairro         : '',
      complemento    : '',
      cidade         : '',
      uf             : '',
      id_profissao   : null
    },
    currentStep: 1,
    totalSteps : 3
  },

  getters: {
    isLoading     : state => state.loading,
    hasError      : state => state.error !== null,
    getError      : state => state.error,
    getClientes   : state => state.clientes,
    getCliente    : state => state.cliente,
    getStats      : state => state.stats,
    getCurrentStep: state => state.currentStep,
    getTotalSteps : state => state.totalSteps,
    getFormData   : state => state.formData
  },

  mutations: {
    SET_LOADING(state, loading) {
      state.loading = loading;
    },

    SET_ERROR(state, error) {
      state.error = error;
    },

    SET_CLIENTES(state, clientes) {
      state.clientes = clientes;
    },

    SET_CLIENTE(state, cliente) {
      state.cliente = cliente;
    },

    SET_STATS(state, stats) {
      state.stats = stats;
    },

    SET_FORM_DATA(state, payload) {
      Object.assign(state.formData, payload);
    },

    SET_CURRENT_STEP(state, step) {
      state.currentStep = step;
      localStorage.setItem('clienteCurrentStep', step);
    },

    RESET_FORM_DATA(state) {
      state.formData = {
        nome           : '',
        data_nascimento: '',
        tipo_pessoa    : 'fisica',
        documento      : '',
        email          : '',
        telefone       : '',
        endereco       : '',
        numero         : '',
        bairro         : '',
        complemento    : '',
        cidade         : '',
        uf             : '',
        id_profissao   : null
      };
      state.currentStep = 1;
    }
  },

  actions: {
    setCurrentStep({ commit }, step) {
      commit('SET_CURRENT_STEP', step);
    },

    async fetchStats({ commit }) {
      try {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        const response = await clienteService.getStats();
        commit('SET_STATS', response.data);

        return response.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao buscar estatísticas');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },

    async fetchClientes({ commit }, params = {}) {
      try {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        const response = await clienteService.getClientes(params);
        commit('SET_CLIENTES', response.data);

        return response.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao buscar clientes');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },

    async fetchCliente({ commit }, id) {
      try {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        const response = await clienteService.getCliente(id);
        commit('SET_CLIENTE', response.data);

        const cliente = response.data;
        commit('SET_FORM_DATA', {
          nome           : cliente.nome,
          data_nascimento: cliente.data_nascimento,
          tipo_pessoa    : cliente.tipo_pessoa,
          documento      : cliente.documento,
          email          : cliente.email,
          telefone       : cliente.telefone,
          endereco       : cliente.endereco.endereco,
          numero         : cliente.endereco.numero,
          bairro         : cliente.endereco.bairro,
          complemento    : cliente.endereco.complemento,
          cidade         : cliente.endereco.cidade,
          uf             : cliente.endereco.uf,
          id_profissao   : cliente.id_profissao
        });

        return response.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao buscar cliente');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },

    async createCliente({ commit, state }) {
      try {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        const response = await clienteService.createCliente(state.formData);
        commit('RESET_FORM_DATA');
        localStorage.removeItem('clienteFormData');

        return response.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao criar cliente');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },

    async updateCliente({ commit, state }, id) {
      try {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        const response = await clienteService.updateCliente(id, state.formData);
        commit('SET_CLIENTE', response.data.cliente);

        return response.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao atualizar cliente');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },

    async deleteCliente({ commit }, id) {
      try {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);

        const response = await clienteService.deleteCliente(id);

        return response.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao excluir cliente');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    },

    updateFormData({ commit, state }, data) {
      commit('SET_FORM_DATA', data);
      localStorage.setItem('clienteFormData', JSON.stringify(state.formData));
    },

    nextStep({ commit, state }) {
      if (state.currentStep < state.totalSteps) {
        commit('SET_CURRENT_STEP', state.currentStep + 1);
      }
    },

    previousStep({ commit, state }) {
      if (state.currentStep > 1) {
        commit('SET_CURRENT_STEP', state.currentStep - 1);
      }
    },

    resetForm({ commit }) {
      commit('RESET_FORM_DATA');
      localStorage.removeItem('clienteFormData');
      localStorage.removeItem('clienteCurrentStep');
    },

    resetSteps({ commit }) {
      commit('SET_CURRENT_STEP', 1);              
      localStorage.removeItem('clienteCurrentStep'); 
    },

    loadSavedStep({ commit }) {
      const step = parseInt(localStorage.getItem('clienteCurrentStep'));
      if (!isNaN(step) && step >= 1 && step <= 3) {
        commit('SET_CURRENT_STEP', step);
      }
    },

    loadSavedFormData({ commit }) {
      const saved = localStorage.getItem('clienteFormData');
      if (saved) {
        try {
          const parsed = JSON.parse(saved);
          commit('SET_FORM_DATA', parsed);
        } catch (error) {
          console.error('Erro ao parsear dados salvos:', error);
          localStorage.removeItem('clienteFormData');
        }
      }
    }
  }
};