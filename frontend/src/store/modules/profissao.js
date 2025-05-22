import profissaoService from '@/services/profissaoService';

export default {
  namespaced: true,
  
  state: {
    profissoes: [],
    loading: false,
    error: null
  },
  
  getters: {
    isLoading: state => state.loading,
    hasError: state => state.error !== null,
    getError: state => state.error,
    getProfissoes: state => state.profissoes
  },
  
  mutations: {
    SET_LOADING(state, loading) {
      state.loading = loading;
    },
    
    SET_ERROR(state, error) {
      state.error = error;
    },
    
    SET_PROFISSOES(state, profissoes) {
      state.profissoes = profissoes;
    }
  },
  
  actions: {
    async fetchProfissoes({ commit }) {
      try {
        commit('SET_LOADING', true);
        commit('SET_ERROR', null);
        
        const response = await profissaoService.getProfissoes();
        commit('SET_PROFISSOES', response.data);
        
        return response.data;
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao buscar profissões');
        throw error;
      } finally {
        commit('SET_LOADING', false);
      }
    }
  }
};