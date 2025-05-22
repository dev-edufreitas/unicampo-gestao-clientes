<template>
  <div class="dados-pessoais-form">
    <!-- Cabeçalho da Seção -->
    <div class="section-header mb-4">
      <div class="d-flex align-items-center mb-3">
        <div class="section-icon">
          <i class="fas fa-user"></i>
        </div>
        <div>
          <h4 class="section-title mb-1">Dados Pessoais</h4>
          <p class="section-description mb-0">Informações básicas do cliente</p>
        </div>
      </div>
    </div>

    <!-- Formulário -->
    <div class="form-grid">
      <div class="row g-4">
        <!-- Nome Completo -->
        <div class="col-12">
          <InputField id="nome" label="Nome Completo" v-model="form.nome" :maxlength="255" :error="errors.nome"
            placeholder="Digite o nome completo" prepend-icon="fas fa-user" required />
        </div>

        <!-- Data de Nascimento e Tipo de Pessoa -->
        <div class="col-md-6">
          <InputField id="data_nascimento" label="Data de Nascimento" type="date" v-model="form.data_nascimento"
            :error="errors.data_nascimento" required />
        </div>

        <div class="col-md-6">
          <SelectField id="tipo_pessoa" label="Tipo de Pessoa" v-model="form.tipo_pessoa" :options="tiposPessoa"
            :error="errors.tipo_pessoa" required @update:modelValue="resetDocumento" />
        </div>

        <!-- Documento -->
        <div class="col-md-6">
          <InputField id="documento" :label="documentoLabel" v-model="form.documento"
            :maxlength="form.tipo_pessoa === 'fisica' ? 14 : 18" :error="errors.documento"
            :placeholder="documentoPlaceholder" prepend-icon="fas fa-id-card" required v-mask="documentoMask" />
        </div>

        <!-- Email -->
        <div class="col-md-6">
          <InputField id="email" label="Email" type="email" v-model="form.email" :error="errors.email"
            placeholder="exemplo@email.com" prepend-icon="fas fa-envelope" required />
        </div>

        <!-- Telefone -->
        <div class="col-12">
          <InputField id="telefone" label="Telefone" v-model="form.telefone" :error="errors.telefone"
            placeholder="(00) 00000-0000" prepend-icon="fas fa-phone" required v-mask="'(##) #####-####'" />
        </div>
      </div>
    </div>

    <!-- Ações -->
    <div class="form-actions">
      <div class="d-flex justify-content-end gap-3">
        <router-link to="/clientes" class="btn btn-outline-secondary">
          <i class="fas fa-times me-2"></i>
          Cancelar
        </router-link>
        <button type="button" class="btn btn-unicampo" @click="prosseguir" :disabled="!isFormValid">
          Próximo
          <i class="fas fa-arrow-right ms-2"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import InputField from '@/components/ui/InputField.vue';
import SelectField from '@/components/ui/SelectField.vue';
import { mask } from 'vue-the-mask';
import { watch } from 'vue';

import { useValidacaoCliente } from '@/composables/useValidacaoCliente';

export default {
  name: 'DadosPessoaisForm',
  components: {
    InputField,
    SelectField
  },
  directives: {
    mask
  },
  setup() {
    const store = useStore();
    const formData = store.getters['cliente/getFormData'];

    const form = ref({ ...formData });

    watch(
      () => store.getters['cliente/getFormData'],
      (novoValor) => {
        form.value = { ...novoValor };
      },
      { deep: true, immediate: true }
    );

    const errors = ref({
      nome: '',
      data_nascimento: '',
      tipo_pessoa: '',
      documento: '',
      email: '',
      telefone: ''
    });

    const tiposPessoa = [
      { value: 'fisica', label: 'Pessoa Física' },
      { value: 'juridica', label: 'Pessoa Jurídica' }
    ];

    watch(
      form,
      (val) => {
        const hasValue = Object.values(val).some(v => v !== '');
        if (hasValue) {
          store.dispatch('cliente/updateFormData', val);
        }
      },
      { deep: true }
    );

    const documentoLabel = computed(() => form.value.tipo_pessoa === 'fisica' ? 'CPF' : 'CNPJ');
    const documentoPlaceholder = computed(() => form.value.tipo_pessoa === 'fisica' ? '000.000.000-00' : '00.000.000/0000-00');
    const documentoMask = computed(() => form.value.tipo_pessoa === 'fisica' ? '###.###.###-##' : '##.###.###/####-##');

    const isFormValid = computed(() => {
      return form.value.nome &&
        form.value.data_nascimento &&
        form.value.tipo_pessoa &&
        form.value.documento &&
        form.value.email &&
        form.value.telefone &&
        Object.values(errors.value).every(e => !e);
    });

    const resetDocumento = () => {
      form.value.documento = '';
      errors.value.documento = '';
    };

    const prosseguir = () => {
      if (isFormValid.value) {
        store.dispatch('cliente/updateFormData', form.value);
        store.dispatch('cliente/nextStep');
      }
    };

    useValidacaoCliente(form, errors, documentoLabel);

    return {
      form,
      errors,
      tiposPessoa,
      documentoLabel,
      documentoPlaceholder,
      documentoMask,
      isFormValid,
      resetDocumento,
      prosseguir
    };
  }
};
</script>

<style scoped>
.section-header {
  border-bottom: 2px solid #f8f9fa;
  padding-bottom: 1rem;
}

.section-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: linear-gradient(135deg, #0a3b25 0%, #0d4a2d 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  margin-right: 1rem;
  font-size: 1.2rem;
}

.section-title {
  color: #0a3b25;
  font-weight: 600;
  margin: 0;
}

.section-description {
  color: #6c757d;
  font-size: 0.9rem;
}

.form-grid {
  margin-bottom: 2rem;
}

.form-actions {
  border-top: 2px solid #f8f9fa;
  padding-top: 2rem;
}

.btn-unicampo {
  background: linear-gradient(135deg, #0a3b25 0%, #0d4a2d 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  padding: 0.75rem 2rem;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(10, 59, 37, 0.1);
}

.btn-unicampo:hover:not(:disabled) {
  background: linear-gradient(135deg, #083d26 0%, #0a4228 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(10, 59, 37, 0.2);
  color: white;
}

.btn-unicampo:disabled {
  background: #6c757d;
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-outline-secondary {
  border-radius: 8px;
  font-weight: 500;
  padding: 0.75rem 2rem;
  transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
  .form-actions .d-flex {
    flex-direction: column;
    gap: 1rem;
  }

  .form-actions .btn {
    width: 100%;
  }

  .section-icon {
    width: 40px;
    height: 40px;
    font-size: 1rem;
  }
}
</style>