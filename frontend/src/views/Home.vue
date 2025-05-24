<template>
  <div class="home pt-lg-5 pt-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
      <div class="card-body p-0">
        <div class="row g-0 align-items-center">
          <div class="col-lg-6 p-5">
            <h1 class="display-4 text-unicampo mb-3">Sistema de Gerenciamento</h1>
            <h2 class="h4 fw-light text-secondary mb-4">Gerencie seus clientes de forma inteligente</h2>
            <p class="text-muted fs-6 mb-4">
              Bem-vindo ao Sistema de Gerenciamento de Clientes da UNICAMPO.
              Organize, monitore e atenda seus clientes com eficiência.
            </p>
            <div class="d-flex flex-wrap gap-3 mb-4">
              <PrincipalButton to="/clientes" class="px-4 py-3" icon="fas fa-user">
                Ver Clientes
              </PrincipalButton>
              <SecondaryButton @click="irParaNovoCliente" class="px-4 py-3" icon="fas fa-user-plus">
                Novo Cliente
              </SecondaryButton>
            </div>
          </div>
          <div class="col-lg-6">
            <img src="@/assets/gerenciamento.png" alt="Gerenciamento de Clientes" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
    <section class="funcionalidades-section">
      <div class="container-fluid px-0">
        <div class="text-center mb-5">
          <h2 class="section-title">Funcionalidades do Sistema</h2>
          <p class="text-muted mt-3">Explore todas as ferramentas disponíveis</p>
        </div>
        <div class="row g-4 px-3">
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 funcionalidade-card shadow-sm">
              <div class="card-body text-center p-4 d-flex flex-column">
                <div class="icone-wrapper mb-4 mx-auto">
                  <i class="fas fa-user-plus fa-2x text-unicampo"></i>
                </div>
                <h3 class="h4 mb-3 text-unicampo fw-semibold">Cadastrar Clientes</h3>
                <p class="text-muted mb-4 flex-grow-1">
                  Adicione novos clientes ao sistema com facilidade e mantenha seus dados sempre atualizados para um controle eficiente.
                </p>
                <div class="mt-auto">
                  <button @click="irParaNovoCliente" class="btn btn-funcao w-100">
                    Cadastrar Agora
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 funcionalidade-card shadow-sm">
              <div class="card-body text-center p-4 d-flex flex-column">
                <div class="icone-wrapper mb-4 mx-auto">
                  <i class="fas fa-search fa-2x text-unicampo"></i>
                </div>
                <h3 class="h4 mb-3 text-unicampo fw-semibold">Consultar Clientes</h3>
                <p class="text-muted mb-4 flex-grow-1">
                  Visualize e pesquise os clientes cadastrados no sistema de forma rápida e eficiente com filtros avançados.
                </p>
                <div class="mt-auto">
                  <router-link to="/clientes" class="btn btn-funcao w-100">
                    Consultar Lista
                  </router-link>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 funcionalidade-card shadow-sm">
              <div class="card-body text-center p-4 d-flex flex-column">
                <div class="icone-wrapper mb-4 mx-auto">
                  <i class="fas fa-file-alt fa-2x text-unicampo"></i>
                </div>
                <h3 class="h4 mb-3 text-unicampo fw-semibold">Documentação</h3>
                <p class="text-muted mb-4 flex-grow-1">
                  Acesse documentação completa e guias detalhados para utilizar todas as funcionalidades do sistema.
                </p>
                <div class="mt-auto">
                  <a href="http://localhost:8000/api/documentation" target="_blank" rel="noopener noreferrer" class="btn btn-funcao w-100">
                    Ver Documentação <i class="fas fa-external-link-alt ms-2"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="estatisticas-section mt-5">
      <div class="card border-0 rounded-4 bg-light">
        <div class="card-body p-5">
          <div v-if="statsLoading" class="text-center py-4">
            <div class="spinner-border text-unicampo" role="status">
              <span class="visually-hidden">Carregando estatísticas...</span>
            </div>
            <p class="text-muted mt-2">Carregando estatísticas...</p>
          </div>
          <div v-else-if="statsError" class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Erro ao carregar estatísticas. Mostrando dados de exemplo.
          </div>
          <div v-else class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
              <div class="stat-item">
                <h3 class="display-6 fw-bold text-unicampo mb-2">{{ formatNumber(stats.total_clientes) }}</h3>
                <p class="text-muted mb-0">Clientes Cadastrados</p>
              </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
              <div class="stat-item">
                <h3 class="display-6 fw-bold text-unicampo mb-2">{{ formatNumber(stats.clientes_ativos) }}</h3>
                <p class="text-muted mb-0">Clientes Ativos</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-item">
                <h3 class="display-6 fw-bold text-unicampo mb-2">{{ formatNumber(stats.novos_mes) }}</h3>
                <p class="text-muted mb-0">Novos este Mês</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import PrincipalButton from '@/components/ui/PrincipalButton.vue';
import SecondaryButton from '@/components/ui/SecondaryButton.vue';

export default {
  name: 'Home',
  components: {
    PrincipalButton,
    SecondaryButton
  },
  setup() {
    const router = useRouter();
    const store  = useStore();

    const stats        = computed(() => store.getters['cliente/getStats']);
    const statsLoading = computed(() => store.getters['cliente/isLoading']);
    const statsError   = computed(() => store.getters['cliente/hasError']);

    const irParaNovoCliente = async () => {
      try {
        await store.dispatch('cliente/resetForm')
        await router.push({ path: '/clientes/novo' })
      } catch (error) {
        console.error('Erro ao ir para novo cliente:', error)
      }
    }
    
    const formatNumber = (number) => {
      if (number === 0) return '0';
      return number.toLocaleString('pt-BR');
    };

    onMounted(async () => {
      try {
        await store.dispatch('cliente/fetchStats');
      } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
      }
    });

    return {
      stats,
      statsLoading,
      statsError,
      irParaNovoCliente,
      formatNumber
    };
  }
};
</script>

<style scoped>
.icone-wrapper {
  background-color: rgba(10, 59, 37, 0.1);
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.section-title {
  color: #0a3b25;
  font-weight: 600;
  position: relative;
  display: inline-block;
  padding-bottom: 12px;
}

.section-title::after {
  content: '';
  position: absolute;
  height: 3px;
  width: 60px;
  background-color: #0a3b25;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 3px;
}

.funcionalidade-card {
  border-radius: 16px;
  transition: all 0.3s ease;
}

.funcionalidade-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.stat-item {
  padding: 1rem;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.stat-item:hover {
  background-color: white;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.funcionalidades-section {
  margin-bottom: 3rem;
}

.estatisticas-section,
.acoes-rapidas-section {
  margin-bottom: 2rem;
}

@media (max-width: 992px) {
  .home {
    padding-top: 180px;
  }

  .funcionalidades-section .row {
    justify-content: center;
  }
}

@media (max-width: 768px) {
  .display-4 {
    font-size: 2rem;
  }

  .display-6 {
    font-size: 1.5rem;
  }
}
</style>