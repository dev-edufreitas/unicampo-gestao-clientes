<template>
  <div class="profissao-form">
    <div class="section-header mb-4">
      <div class="d-flex align-items-center mb-3">
        <div class="section-icon">
          <i class="fas fa-briefcase"></i>
        </div>
        <div>
          <h4 class="section-title mb-1">Profissão</h4>
          <p class="section-description mb-0">Informações profissionais do cliente</p>
        </div>
      </div>
    </div>

    <div class="form-grid">
      <div class="row g-4">
        <div class="col-12">
          <SelectField
            id="id_profissao"
            label="Profissão"
            v-model="form.id_profissao"
            :options="profissoesOptions"
            :error="errors.id_profissao"
            required
          />
          <div class="info-box mt-3">
            <div class="info-header">
              <i class="fas fa-info-circle me-2"></i>
              <strong>Informação</strong>
            </div>
            <p class="info-content">
              Selecione a profissão que melhor descreve a atividade principal do cliente.
              Esta informação é importante para segmentação e relatórios.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="resumo-dados">
      <div class="resumo-header">
        <i class="fas fa-clipboard-check me-2"></i>
        <strong>Resumo dos Dados Cadastrais</strong>
      </div>

      <div class="resumo-content">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="resumo-section">
              <h6 class="resumo-section-title">
                <i class="fas fa-user me-2"></i>
                Dados Pessoais
              </h6>
              <div class="resumo-item">
                <span class="label">Nome:</span>
                <span class="value">{{ dadosResumo.nome || 'Não informado' }}</span>
              </div>
              <div class="resumo-item">
                <span class="label">{{ dadosResumo.tipo_pessoa === 'fisica' ? 'CPF' : 'CNPJ' }}:</span>
                <span class="value">{{ dadosResumo.documento || 'Não informado' }}</span>
              </div>
              <div class="resumo-item">
                <span class="label">Email:</span>
                <span class="value">{{ dadosResumo.email || 'Não informado' }}</span>
              </div>
              <div class="resumo-item">
                <span class="label">Telefone:</span>
                <span class="value">{{ dadosResumo.telefone || 'Não informado' }}</span>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="resumo-section">
              <h6 class="resumo-section-title">
                <i class="fas fa-map-marker-alt me-2"></i>
                Endereço
              </h6>
              <div class="resumo-item">
                <span class="label">Logradouro:</span>
                <span class="value">{{ enderecoCompleto || 'Não informado' }}</span>
              </div>
              <div class="resumo-item">
                <span class="label">Cidade/UF:</span>
                <span class="value">
                  {{ dadosResumo.cidade && dadosResumo.uf ? `${dadosResumo.cidade}/${dadosResumo.uf}` : 'Não informado' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary" @click="voltar">
          <i class="fas fa-arrow-left me-2"></i> Voltar
        </button>
        <PrincipalButton type="button" icon="fas fa-save" :disabled="!isFormValid || loading" @click="handleSalvar">
          {{ isEdicao ? 'Atualizar Cliente' : 'Cadastrar Cliente' }}
        </PrincipalButton>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';
import SelectField from '@/components/ui/SelectField.vue';
import PrincipalButton from '@/components/ui/PrincipalButton.vue';
import eventBus from '@/utils/eventBus';

export default {
  name: 'ProfissaoForm',
  components: {
    SelectField,
    PrincipalButton
  },
  setup() {
    const store  = useStore();
    const router = useRouter();
    const route  = useRoute();

    const formData = store.getters['cliente/getFormData'];
    const form = ref({ id_profissao: formData.id_profissao || '' });
    const errors = ref({ id_profissao: '' });
    const loading = ref(false);

    const profissoesOptions = computed(() => {
      const opcoes = [{ value: '', label: 'Selecione uma profissão' }];
      const profissoes = store.getters['profissao/getProfissoes'].map(p => ({
        value: p.id,
        label: p.nome_profissao
      }));
      return [...opcoes, ...profissoes];
    });

    const dadosResumo = computed(() => store.getters['cliente/getFormData']);
    const enderecoCompleto = computed(() => {
      const dados = dadosResumo.value;
      if (dados.endereco && dados.numero) {
        let endereco = `${dados.endereco}, ${dados.numero}`;
        if (dados.complemento) endereco += `, ${dados.complemento}`;
        if (dados.bairro) endereco += ` - ${dados.bairro}`;
        return endereco;
      }
      return '';
    });

    const isEdicao = computed(() => !!route.params.id);
    const isFormValid = computed(() => form.value.id_profissao && !Object.values(errors.value).some(e => e !== ''));

    const handleSalvar = () => {
      Object.keys(localStorage).forEach(key => {
        if (key.includes('Form')) localStorage.removeItem(key);
      });
      salvar();
    };

    const validarForm = () => {
      errors.value.id_profissao = '';
      if (!form.value.id_profissao) {
        errors.value.id_profissao = 'A profissão é obrigatória';
        return false;
      }
      return true;
    };

    const voltar = () => {
      store.dispatch('cliente/updateFormData', form.value);
      store.dispatch('cliente/previousStep');
    };

    const salvar = async () => {
      if (!validarForm()) return;

      loading.value = true;
      store.dispatch('cliente/updateFormData', form.value);

      try {
        const id = route.params.id;
        if (id) {
          await store.dispatch('cliente/updateCliente', id);
          eventBus.emit('notify', { message: 'Cliente atualizado com sucesso!' });
        } else {
          await store.dispatch('cliente/createCliente');
          eventBus.emit('notify', { message: 'Cliente cadastrado com sucesso!' });
        }

        store.dispatch('cliente/resetForm');
        setTimeout(() => router.push({ name: 'clientes' }), 1500);

      } catch (error) {
        console.error('Erro ao salvar cliente:', error);

        if (error.response?.data?.errors) {
          const apiErrors = error.response.data.errors;
          Object.keys(apiErrors).forEach(key => {
            if (errors.value.hasOwnProperty(key)) {
              errors.value[key] = apiErrors[key][0];
            }
          });
          eventBus.emit('notify', {
            message: 'Verifique os dados informados.',
            type: 'error',
            duration: 5000
          });
        } else {
          eventBus.emit('notify', {
            message: 'Erro interno. Tente novamente em alguns instantes.',
            type: 'error',
            duration: 5000
          });
        }

      } finally {
        loading.value = false;
      }
    };

    watch(form, (newValue) => {
      const hasValue = Object.values(newValue).some(v => v !== '' && v !== null);
      if (hasValue) store.dispatch('cliente/updateFormData', newValue);
      validarForm();
    }, { deep: true });

    return {
      form,
      errors,
      loading,
      profissoesOptions,
      dadosResumo,
      enderecoCompleto,
      isEdicao,
      isFormValid,
      handleSalvar,
      voltar
    };
  }
};
</script>

<style scoped>
@import '@/assets/css/components/profissao.css';
</style>
