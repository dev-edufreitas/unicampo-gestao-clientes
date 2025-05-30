<template>
  <div class="cliente-list pt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <h1 class="h2 fw-bold text-unicampo mb-2">Lista de Clientes</h1>
        <p class="text-muted mb-0">Gerencie e visualize todos os seus clientes cadastrados</p>
      </div>
      <PrincipalButton @click="irParaNovoCliente" icon="fas fa-user-plus">
        Novo Cliente
      </PrincipalButton>
    </div>
    <div class="card border-0 rounded-4 shadow-sm mb-4">
      <div class="card-body">
        <h5 class="mb-4 text-unicampo fw-semibold">
          <i class="fas fa-filter me-2"></i>Filtros de Busca
        </h5>
        <form @submit.prevent="buscarClientes">
          <div class="row g-4">
            <div class="col-lg-3">
              <InputField v-model="filtros.nome" label="Nome do Cliente" placeholder="Buscar por nome..."
                prepend-icon="fas fa-search" />
            </div>
            <div class="col-lg-3">
              <SelectField v-model="filtros.status" label="Status" :options="[
                { value: '', label: 'Todos os status' },
                { value: 'ativo', label: 'Ativo' },
                { value: 'inativo', label: 'Inativo' }
              ]" />
            </div>
            <div class="col-lg-3">
              <SelectField v-model="filtros.order_desc" label="Ordenação" :options="[
                { value: true, label: 'Mais recentes primeiro' },
                { value: false, label: 'Mais antigos primeiro' }
              ]" />
            </div>
            <div class="col-md-3">
              <label class="form-label">Ações</label>
              <div class="d-flex gap-2 align-items-center ">
                <PrincipalButton type="submit" icon="fas fa-search" class="w-100">
                  Buscar
                </PrincipalButton>
                <SecondaryButton type="button" icon="fas fa-eraser me-1" class="w-100" @click="limparFiltros">
                  Limpar
                </SecondaryButton>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-unicampo" role="status">
        <span class="visually-hidden">Carregando...</span>
      </div>
      <p class="text-muted mt-3">Carregando clientes...</p>
    </div>
    <div v-else-if="error" class="alert alert-danger d-flex align-items-center" role="alert">
      <i class="fas fa-exclamation-triangle me-3"></i>
      <div><strong>Erro:</strong> {{ error }}</div>
    </div>
    <div v-else-if="clientes.length === 0" class="card shadow-sm">
      <div class="card-body text-center py-5">
        <i class="fas fa-users fa-3x text-muted mb-3"></i>
        <h5 class="card-title text-muted">Nenhum cliente encontrado</h5>
        <p class="text-muted mb-4">Tente ajustar os filtros ou cadastre um novo cliente</p>
        <router-link to="/clientes/novo" class="btn btn-unicampo">
          <i class="fas fa-user-plus me-2"></i> Cadastrar Primeiro Cliente
        </router-link>
      </div>
    </div>
    <div v-else class="card border-0 shadow-sm">
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-unicampo fw-semibold">
          <i class="fas fa-list me-2"></i> {{ clientes.length }} cliente(s) encontrado(s)
        </h6>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-light sticky-top">
            <tr>
              <th scope="col" class="ps-3">#</th>
              <th scope="col">Cliente</th>
              <th scope="col">Documento</th>
              <th scope="col">Contato</th>
              <th scope="col">Profissão</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-center pe-3">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cliente in clientes" :key="cliente.id" class="border-top" tabindex="0"
              @keydown.enter="irParaEditar(cliente.id)">
              <th scope="row" class="text-muted small ps-3">
                #{{ cliente.id }}
              </th>
              <td>
                <div class="fw-semibold">{{ cliente.nome }}</div>
                <div class="text-muted small">{{ cliente.email }}</div>
              </td>
              <td>
                <code class="small">{{ formatarDocumento(cliente.documento) }}</code>
              </td>
              <td>
                <span class="small">{{ cliente.telefone }}</span>
              </td>
              <td>
                <span class="badge bg-secondary bg-opacity-10 text-secondary small">
                  {{ cliente.profissao.nome_profissao }}
                </span>
              </td>
              <td>
                <span class="badge small d-inline-flex align-items-center" :class="cliente.status === 'ativo'
                  ? 'bg-success bg-opacity-10 text-success'
                  : 'bg-danger bg-opacity-10 text-danger'">
                  <i class="fas" :class="cliente.status === 'ativo' ? 'fa-check-circle me-1' : 'fa-times-circle me-1'"
                    aria-hidden="true"></i>
                  <span class="visually-hidden">Status:</span>
                  {{ cliente.status === 'ativo' ? 'Ativo' : 'Inativo' }}
                </span>
              </td>
              <td class="text-center pe-3">
                <div class="dropstart">
                  <button class="btn btn-sm " type="button" :id="`acoesDropdown-${cliente.id}`"
                    data-bs-toggle="dropdown" data-bs-container="body" aria-expanded="false"
                    :aria-label="`Menu de ações do cliente ${cliente.nome}`">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu" :aria-labelledby="`acoesDropdown-${cliente.id}`"
                    style="z-index: 1050 !important;">
                    <li>
                     <button class="dropdown-item d-flex align-items-center"  @click="editarCliente(cliente.id)">
                        <i class="fas fa-pen me-2"></i>
                        <span>Editar</span>
                        </button>
                    </li>
                    <li v-if="cliente.status === 'ativo'">
                      <button class="dropdown-item text-danger d-flex align-items-center"
                        @click="inativarCliente(cliente.id)">
                        <i class="fas fa-ban me-2"></i>
                        <span>Inativar</span>
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import InputField from '@/components/ui/InputField.vue';
import SelectField from '@/components/ui/SelectField.vue';
import PrincipalButton from '@/components/ui/PrincipalButton.vue';
import SecondaryButton from '@/components/ui/SecondaryButton.vue';

export default {
  name: 'ClienteList',
  components: {
    InputField,
    SelectField,
    PrincipalButton,
    SecondaryButton
  },
  setup() {
    const store    = useStore();
    const router   = useRouter();
    const filtros  = ref({ nome: '', status: '', order_desc: true });
    const clientes = computed(() => store.getters['cliente/getClientes']);
    const loading  = computed(() => store.getters['cliente/isLoading']);
    const error    = computed(() => store.getters['cliente/getError']);

    const irParaNovoCliente = async () => {
      try {
        await store.dispatch('cliente/resetForm')
        await router.push({ path: '/clientes/novo' })
      } catch (error) {
        console.error('Erro ao ir para novo cliente:', error)
      }
    }

    const editarCliente = async (id) => {
      try {
        await store.dispatch('cliente/resetSteps');
        await router.push({ path: `/clientes/${id}/editar` });
      } catch (err) {
        console.error('Erro ao editar cliente:', err);
      }
    };

    const buscarClientes = async () => {
      try {
        await store.dispatch('cliente/fetchClientes', filtros.value);
      } catch (error) {
        console.error('Erro ao buscar clientes:', error);
      }
    };

    const limparFiltros = () => {
      filtros.value = { nome: '', status: '', order_desc: true };
      buscarClientes();
    };

    const inativarCliente = async (id) => {
      if (confirm('Tem certeza que deseja inativar este cliente?')) {
        await store.dispatch('cliente/deleteCliente', id);
        buscarClientes();
      }
    };

    const formatarDocumento = (documento) => {
      const numeros = documento?.replace(/\D/g, '') || '';
      if (numeros.length === 11)
        return numeros.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
      if (numeros.length === 14)
        return numeros.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
      return documento;
    };

    onMounted(() => buscarClientes());

    return {
      filtros,
      clientes,
      loading,
      error,
      buscarClientes,
      limparFiltros,
      inativarCliente,
      formatarDocumento,
      editarCliente,
      irParaNovoCliente
    };
  }
};
</script>

<style scoped></style>
