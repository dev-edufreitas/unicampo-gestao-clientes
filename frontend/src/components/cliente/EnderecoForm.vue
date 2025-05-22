<template>
  <div class="endereco-form">
    <!-- Cabeçalho da Seção -->
    <div class="section-header mb-4">
      <div class="d-flex align-items-center mb-3">
        <div class="section-icon">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <div>
          <h4 class="section-title mb-1">Endereço</h4>
          <p class="section-description mb-0">Informações de localização do cliente</p>
        </div>
      </div>
    </div>

    <!-- Formulário -->
    <div class="form-grid">
      <div class="row g-4">
        <div class="col-md-8">
          <InputField id="endereco" label="Endereço" v-model="form.endereco" :maxlength="255" :error="errors.endereco"
            placeholder="Rua, Avenida, Parque..." prepend-icon="fas fa-road" required />
        </div>

        <div class="col-md-4">
          <InputField id="numero" label="Número" v-model="form.numero" :maxlength="20" :error="errors.numero"
            placeholder="123" prepend-icon="fas fa-hashtag" required />
        </div>

        <div class="col-md-6">
          <InputField id="bairro" label="Bairro" v-model="form.bairro" :maxlength="255" :error="errors.bairro"
            placeholder="Nome do bairro" prepend-icon="fas fa-map" required />
        </div>

        <div class="col-md-6">
          <InputField id="complemento" label="Complemento" v-model="form.complemento" :maxlength="255"
            :error="errors.complemento" placeholder="Apartamento, sala, bloco... (opcional)"
            prepend-icon="fas fa-building" />
        </div>

        <div class="col-md-8">
          <InputField id="cidade" label="Cidade" v-model="form.cidade" :maxlength="255" :error="errors.cidade"
            placeholder="Nome da cidade" prepend-icon="fas fa-city" required />
        </div>

        <div class="col-md-4">
          <SelectField id="uf" label="Estado (UF)" v-model="form.uf" :options="estados" :error="errors.uf" required />
        </div>
      </div>
    </div>

    <div v-if="enderecoCompleto" class="endereco-preview">
      <div class="preview-header">
        <i class="fas fa-eye me-2"></i>
        <strong>Pré-visualização do Endereço:</strong>
      </div>
      <div class="preview-content">{{ enderecoCompleto }}</div>
    </div>

    <!-- Ações -->
    <div class="form-actions">
      <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary" @click="voltar">
          <i class="fas fa-arrow-left me-2"></i>
          Voltar
        </button>
        <button type="button" class="btn btn-unicampo" @click="prosseguir" :disabled="!isFormValid">
          Próximo
          <i class="fas fa-arrow-right ms-2"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue';
import { useStore } from 'vuex';
import InputField from '@/components/ui/InputField.vue';
import SelectField from '@/components/ui/SelectField.vue';
import { useValidacaoEndereco } from '@/composables/useValidacaoEndereco';

export default {
  name: 'EnderecoForm',
  components: { InputField, SelectField },
  setup() {
    const store = useStore();
    const formData = store.getters['cliente/getFormData'];

    const form = ref({
      endereco: formData.endereco || '',
      numero: formData.numero || '',
      bairro: formData.bairro || '',
      complemento: formData.complemento || '',
      cidade: formData.cidade || '',
      uf: formData.uf || ''
    });

    watch(
      () => store.getters['cliente/getFormData'],
      (novo) => {
        form.value = {
          endereco: novo.endereco || '',
          numero: novo.numero || '',
          bairro: novo.bairro || '',
          complemento: novo.complemento || '',
          cidade: novo.cidade || '',
          uf: novo.uf || ''
        };
      },
      { immediate: true }
    );

    const errors = ref({
      endereco: '',
      numero: '',
      bairro: '',
      complemento: '',
      cidade: '',
      uf: ''
    });

    const estados = [
      { value: '', label: 'Selecione o estado' },
      { value: 'AC', label: 'Acre' },
      { value: 'AL', label: 'Alagoas' },
      { value: 'AP', label: 'Amapá' },
      { value: 'AM', label: 'Amazonas' },
      { value: 'BA', label: 'Bahia' },
      { value: 'CE', label: 'Ceará' },
      { value: 'DF', label: 'Distrito Federal' },
      { value: 'ES', label: 'Espírito Santo' },
      { value: 'GO', label: 'Goiás' },
      { value: 'MA', label: 'Maranhão' },
      { value: 'MT', label: 'Mato Grosso' },
      { value: 'MS', label: 'Mato Grosso do Sul' },
      { value: 'MG', label: 'Minas Gerais' },
      { value: 'PA', label: 'Pará' },
      { value: 'PB', label: 'Paraíba' },
      { value: 'PR', label: 'Paraná' },
      { value: 'PE', label: 'Pernambuco' },
      { value: 'PI', label: 'Piauí' },
      { value: 'RJ', label: 'Rio de Janeiro' },
      { value: 'RN', label: 'Rio Grande do Norte' },
      { value: 'RS', label: 'Rio Grande do Sul' },
      { value: 'RO', label: 'Rondônia' },
      { value: 'RR', label: 'Roraima' },
      { value: 'SC', label: 'Santa Catarina' },
      { value: 'SP', label: 'São Paulo' },
      { value: 'SE', label: 'Sergipe' },
      { value: 'TO', label: 'Tocantins' }
    ];

    const enderecoCompleto = computed(() => {
      const partes = [];
      if (form.value.endereco && form.value.numero) partes.push(`${form.value.endereco}, ${form.value.numero}`);
      if (form.value.complemento) partes.push(form.value.complemento);
      if (form.value.bairro) partes.push(form.value.bairro);
      if (form.value.cidade && form.value.uf) partes.push(`${form.value.cidade}/${form.value.uf}`);
      return partes.length > 0 ? partes.join(' - ') : '';
    });

    const isFormValid = computed(() => {
      return form.value.endereco &&
        form.value.numero &&
        form.value.bairro &&
        form.value.cidade &&
        form.value.uf &&
        Object.values(errors.value).every(e => !e);
    });

    const voltar = () => {
      store.dispatch('cliente/updateFormData', form.value);
      store.dispatch('cliente/previousStep');
    };

    const prosseguir = () => {
      if (isFormValid.value) {
        store.dispatch('cliente/updateFormData', form.value);
        store.dispatch('cliente/nextStep');
      }
    };

    watch(
      form,
      (newValue) => {
        const hasValue = Object.values(newValue).some(v => v !== '' && v !== null);
        if (hasValue) {
          store.dispatch('cliente/updateFormData', newValue);
        }
      },
      { deep: true }
    );


    useValidacaoEndereco(form, errors);

    return {
      form,
      errors,
      estados,
      enderecoCompleto,
      isFormValid,
      voltar,
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

.endereco-preview {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border: 1px solid #dee2e6;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.preview-header {
  color: #0a3b25;
  font-weight: 600;
  margin-bottom: 0.75rem;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.preview-content {
  color: #495057;
  font-size: 1rem;
  line-height: 1.5;
  padding: 0.75rem;
  background: white;
  border-radius: 8px;
  border-left: 4px solid #0a3b25;
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

  .endereco-preview {
    padding: 1rem;
  }
}
</style>