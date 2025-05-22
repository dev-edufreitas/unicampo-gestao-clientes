<template>
  <div class="cliente-form">
    <h2 v-if="isEdicao">Editar Cliente</h2>
    <h2 v-else>Novo Cliente</h2>
    
    <div class="card">
      <div class="card-header">
        <div class="wizard-steps d-flex justify-content-around">
          <div
            v-for="step in totalSteps"
            :key="step"
            :class="[
              'wizard-step',
              { active: currentStep === step },
              { completed: currentStep > step }
            ]"
          >
            <div class="wizard-step-number">{{ step }}</div>
            <div class="wizard-step-label">
              {{ getStepLabel(step) }}
            </div>
          </div>
        </div>
      </div>
      
      <div class="card-body">
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Carregando...</span>
          </div>
        </div>
        
        <div v-else-if="error" class="alert alert-danger">
          {{ error }}
        </div>
        
        <div v-else>
          <DadosPessoaisForm v-if="currentStep === 1" />
          <EnderecoForm v-else-if="currentStep === 2" />
          <ProfissaoForm v-else-if="currentStep === 3" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import DadosPessoaisForm from '@/components/cliente/DadosPessoaisForm.vue';
import EnderecoForm from '@/components/cliente/EnderecoForm.vue';
import ProfissaoForm from '@/components/cliente/ProfissaoForm.vue';

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
    
    onMounted(async () => {
      // Carregar os dados salvos do localStorage (para recuperação em caso de reload)
      store.dispatch('cliente/loadSavedFormData');
      
      // Resetar o formulário se for um novo cadastro
      if (!isEdicao.value) {
        store.dispatch('cliente/resetForm');
      } else {
        // Carregar dados do cliente para edição
        try {
          await store.dispatch('cliente/fetchCliente', route.params.id);
        } catch (error) {
          console.error('Erro ao carregar cliente:', error);
        }
      }
      
      // Carregar profissões
      try {
        await store.dispatch('profissao/fetchProfissoes');
      } catch (error) {
        console.error('Erro ao carregar profissões:', error);
      }
    });
    
    return {
      currentStep,
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
.wizard-steps {
  width: 100%;
}

.wizard-step {
  text-align: center;
  position: relative;
  flex: 1;
}

.wizard-step-number {
  width: 40px;
  height: 40px;
  line-height: 40px;
  border-radius: 50%;
  background-color: #e9ecef;
  color: #6c757d;
  font-weight: bold;
  margin: 0 auto 10px;
}

.wizard-step.active .wizard-step-number {
  background-color: #007bff;
  color: white;
}

.wizard-step.completed .wizard-step-number {
  background-color: #28a745;
  color: white;
}

.wizard-step.completed::after {
  content: '\f00c'; /* FontAwesome checkmark */
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
  position: absolute;
  top: 10px;
  right: 10px;
  color: #28a745;
}

.wizard-step-label {
  font-size: 14px;
  color: #6c757d;
}

.wizard-step.active .wizard-step-label {
  color: #007bff;
  font-weight: bold;
}

.wizard-step.completed .wizard-step-label {
  color: #28a745;
}
</style>