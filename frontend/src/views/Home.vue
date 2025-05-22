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
          <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
              <div class="stat-item">
                <h3 class="display-6 fw-bold text-unicampo mb-2">{{ stats.totalClientes }}</h3>
                <p class="text-muted mb-0">Clientes Cadastrados</p>
              </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
              <div class="stat-item">
                <h3 class="display-6 fw-bold text-unicampo mb-2">{{ stats.clientesAtivos }}</h3>
                <p class="text-muted mb-0">Clientes Ativos</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-item">
                <h3 class="display-6 fw-bold text-unicampo mb-2">{{ stats.novosMes }}</h3>
                <p class="text-muted mb-0">Novos este Mês</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import PrincipalButton from '@/components/ui/PrincipalButton.vue';

const router = useRouter();
const store  = useStore();

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

const stats = ref({
  totalClientes: '1,247',
  clientesAtivos: '1,089',
  novosMes: '43',
});
</script>


<style scoped>
.text-unicampo {
  color: #0a3b25;
}

.btn-unicampo {
  background-color: #0a3b25;
  color: white;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-unicampo-outline {
  background-color: white;
  color: #0a3b25;
  border: 1px solid #0a3b25;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-funcao {
  background-color: #f5f5f5;
  color: #0a3b25;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.2s ease;
  border: 1px solid transparent;
}

.btn-funcao:hover {
  background-color: #0a3b25;
  color: white;
  border-color: #0a3b25;
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

.icone-wrapper {
  background-color: rgba(10, 59, 37, 0.1);
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.funcionalidade-card {
  border-radius: 16px;
  transition: all 0.3s ease;
}

.funcionalidade-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.funcionalidades-section {
  margin-bottom: 3rem;
}

.estatisticas-section,
.acoes-rapidas-section {
  margin-bottom: 2rem;
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