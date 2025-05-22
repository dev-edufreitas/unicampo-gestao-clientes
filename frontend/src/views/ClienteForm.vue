<template>
  <div class="cliente-form pt-4" v-if="currentStepLocal !== null">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <h1 class="h2 fw-bold text-unicampo mb-2">
          {{ isEdicao ? 'Editar Cliente' : 'Novo Cliente' }}
        </h1>
        <p class="text-muted mb-0">
          {{ isEdicao ? 'Atualize as informações do cliente' : 'Preencha os dados para cadastrar um novo cliente' }}
        </p>
      </div>
      <router-link to="/clientes" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Voltar para Lista
      </router-link>
    </div>

    <!-- Wizard de Etapas -->
    <div class="card border-0 rounded-4 shadow-sm mb-4">
      <div class="card-header bg-light border-0 rounded-top-4">
        <div class="wizard-container">
          <div class="wizard-steps">
            <div v-for="step in totalSteps" :key="step" :class="[
              'wizard-step',
              { 'active': currentStepLocal === step },
              { 'completed': currentStepLocal > step }
            ]">
              <div class="step-connector" v-if="step < totalSteps"></div>
              <div class="step-circle">
                <i v-if="currentStepLocal > step" class="fas fa-check"></i>
                <span v-else>{{ step }}</span>
              </div>
              <div class="step-label">
                {{ getStepLabel(step) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Conteúdo do Formulário -->
    <div class="card border-0 rounded-4 shadow-sm">
      <div class="card-body p-5">
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-unicampo" role="status">
            <span class="visually-hidden">Carregando...</span>
          </div>
          <p class="text-muted mt-3">Carregando dados...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger d-flex align-items-center" role="alert">
          <i class="fas fa-exclamation-triangle me-3"></i>
          <div><strong>Erro:</strong> {{ error }}</div>
        </div>

        <div v-else>
          <DadosPessoaisForm v-if="currentStepLocal === 1" />
          <EnderecoForm v-else-if="currentStepLocal === 2" />
          <ProfissaoForm v-else-if="currentStepLocal === 3" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref, watch } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import DadosPessoaisForm from '@/components/cliente/DadosPessoaisForm.vue';
import EnderecoForm from '@/components/cliente/EnderecoForm.vue';
import ProfissaoForm from '@/components/cliente/ProfissaoForm.vue';
import { useClienteFormPersistence } from '@/composables/useClienteFormPersistence';

export default {
  name: 'ClienteForm',
  components: {
    DadosPessoaisForm,
    EnderecoForm,
    ProfissaoForm
  },
  setup() {
    const store = useStore();
    const route = useRoute();
    const currentStepLocal = ref(null);

    const currentStep = computed(() => store.getters['cliente/getCurrentStep']);
    const totalSteps = computed(() => store.getters['cliente/getTotalSteps']);
    const loading = computed(() => store.getters['cliente/isLoading']);
    const error = computed(() => store.getters['cliente/getError']);
    const isEdicao = computed(() => !!route.params.id);

    const getStepLabel = (step) => {
      switch (step) {
        case 1:
          return 'Dados Pessoais';
        case 2:
          return 'Endereço';
        case 3:
          return 'Profissão';
        default:
          return '';
      }
    };

    watch(
      () => currentStep.value,
      (step) => {
        if (step > 0) currentStepLocal.value = step;
      },
      { immediate: true }
    );

    useClienteFormPersistence();

    return {
      currentStep,
      currentStepLocal,
      totalSteps,
      loading,
      error,
      isEdicao,
      getStepLabel
    };
  }
};
</script>



<style scoped>
.text-unicampo {
  color: #0a3b25;
}

.wizard-container {
  padding: 2rem 1rem;
}

.wizard-steps {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  max-width: 600px;
  margin: 0 auto;
}

.wizard-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  flex: 1;
  z-index: 2;
}

.step-connector {
  position: absolute;
  top: 20px;
  left: 50%;
  right: -50%;
  height: 2px;
  background-color: #e9ecef;
  z-index: -1;
}

.wizard-step.completed .step-connector {
  background-color: #0a3b25;
}

.wizard-step.active .step-connector {
  background: linear-gradient(to right, #0a3b25 50%, #e9ecef 50%);
}

.step-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #e9ecef;
  color: #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  margin-bottom: 12px;
  transition: all 0.3s ease;
  border: 3px solid transparent;
}

.wizard-step.active .step-circle {
  background-color: #0a3b25;
  color: white;
  border-color: rgba(10, 59, 37, 0.2);
  box-shadow: 0 0 0 4px rgba(10, 59, 37, 0.1);
}

.wizard-step.completed .step-circle {
  background-color: #0a3b25;
  color: white;
}

.step-label {
  font-size: 14px;
  font-weight: 500;
  color: #6c757d;
  text-align: center;
  transition: color 0.3s ease;
}

.wizard-step.active .step-label {
  color: #0a3b25;
  font-weight: 600;
}

.wizard-step.completed .step-label {
  color: #0a3b25;
}

@media (max-width: 768px) {
  .wizard-container {
    padding: 1rem 0.5rem;
  }

  .step-circle {
    width: 32px;
    height: 32px;
    font-size: 14px;
  }

  .step-label {
    font-size: 12px;
  }

  .cliente-form .d-flex.justify-content-between {
    flex-direction: column;
    gap: 1rem;
  }

  .cliente-form .d-flex.justify-content-between .btn {
    align-self: flex-start;
  }
}
</style>