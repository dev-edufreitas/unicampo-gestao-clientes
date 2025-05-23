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
              <PrincipalButton to="/clientes" icon="fas fa-user">
                Ver Clientes
              </PrincipalButton>
              <button class="btn btn-unicampo-outline px-4 py-3" @click="irParaNovoCliente">
                <i class="fas fa-user-plus me-2"></i> Novo Cliente
              </button>
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
          <div class="col-lg-4 col-md-6" v-for="(card, index) in cards" :key="index">
            <div class="card h-100 border-0 funcionalidade-card shadow-sm">
              <div class="card-body text-center p-4 d-flex flex-column">
                <div class="icone-wrapper mb-4 mx-auto">
                  <i :class="`fas ${card.icon} fa-2x text-unicampo`"></i>
                </div>
                <h3 class="h4 mb-3 text-unicampo fw-semibold">{{ card.title }}</h3>
                <p class="text-muted mb-4 flex-grow-1">{{ card.description }}</p>
                <div class="mt-auto">
                  <router-link :to="card.link" class="btn btn-funcao w-100">
                    {{ card.cta }}
                  </router-link>
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
          <!-- Loading State -->
          <div v-if="statsLoading" class="text-center py-4">
            <div class="spinner-border text-unicampo" role="status">
              <span class="visually-hidden">Carregando estatísticas...</span>
            </div>
            <p class="text-muted mt-2">Carregando estatísticas...</p>
          </div>

          <!-- Error State -->
          <div v-else-if="statsError" class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Erro ao carregar estatísticas. Mostrando dados de exemplo.
          </div>

          <!-- Stats Display -->
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
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import PrincipalButton from '@/components/ui/PrincipalButton.vue';

export default {
  name: 'Home',
  components: {
    PrincipalButton
  },
  setup() {
    const router = useRouter();
    const store = useStore();

    // Estados computados do Vuex
    const stats = computed(() => store.getters['cliente/getStats']);
    const statsLoading = computed(() => store.getters['cliente/isLoading']);
    const statsError = computed(() => store.getters['cliente/hasError']);

    const irParaNovoCliente = () => {
      store.dispatch('cliente/resetForm');
      router.push({ path: '/clientes/novo', query: { clean: '1' } });
    };

    const cards = ref([
      {
        icon: 'fa-user-plus',
        title: 'Cadastrar Clientes',
        description:
          'Adicione novos clientes ao sistema com facilidade e mantenha seus dados sempre atualizados para um controle eficiente.',
        link: '/clientes/novo',
        cta: 'Cadastrar Agora',
      },
      {
        icon: 'fa-search',
        title: 'Consultar Clientes',
        description:
          'Visualize e pesquise os clientes cadastrados no sistema de forma rápida e eficiente com filtros avançados.',
        link: '/clientes',
        cta: 'Consultar Lista',
      },
      {
        icon: 'fa-file-alt',
        title: 'Documentação',
        description:
          'Acesse documentação completa e guias detalhados para utilizar todas as funcionalidades do sistema.',
        link: '/api/documentation',
        cta: 'Ver Documentação',
      },
    ]);

    // Função para formatar números
    const formatNumber = (number) => {
      if (number === 0) return '0';
      return number.toLocaleString('pt-BR');
    };

    // Buscar estatísticas ao montar o componente
    onMounted(async () => {
      try {
        await store.dispatch('cliente/fetchStats');
      } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
      }
    });

    return {
      cards,
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
@import '@/assets/css/view/home.css';
</style>