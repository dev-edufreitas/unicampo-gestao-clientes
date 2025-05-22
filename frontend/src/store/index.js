import { createStore } from 'vuex';
import clienteModule from './modules/cliente';
import profissaoModule from './modules/profissao';

export default createStore({
  modules: {
    cliente: clienteModule,
    profissao: profissaoModule
  }
});