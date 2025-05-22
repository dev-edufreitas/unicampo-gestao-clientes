<template>
  <div class="profissao-form">
    <!-- Cabeçalho da Seção -->
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

    <!-- Formulário -->
    <div class="form-grid">
      <div class="row g-4">
        <div class="col-12">
          <SelectField id="id_profissao" label="Profissão" v-model="form.id_profissao" :options="profissoesOptions"
            :error="errors.id_profissao" required />

          <!-- Informação adicional -->
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

    <!-- Resumo dos Dados -->
    <div class="resumo-dados">
      <div class="resumo-header">
        <i class="fas fa-clipboard-check me-2"></i>
        <strong>Resumo dos Dados Cadastrais</strong>
      </div>

      <div class="resumo-content">
        <div class="row g-3">
          <!-- Dados Pessoais -->
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

          <!-- Endereço -->
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
                  {{ dadosResumo.cidade && dadosResumo.uf ? `${dadosResumo.cidade}/${dadosResumo.uf}` : 'Não informado'
                  }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ações -->
    <div class="form-actions">
      <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary" @click="voltar">
          <i class="fas fa-arrow-left me-2"></i>
          Voltar
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
import PrincipalButton from '../ui/PrincipalButton.vue';
import SelectField from '@/components/ui/SelectField.vue';


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

    const form = ref({
      id_profissao: formData.id_profissao || ''
    });

    const errors = ref({
      id_profissao: ''
    });

    const handleSalvar = () => {
      Object.keys(localStorage).forEach(key => {
        if (key.includes('Form')) localStorage.removeItem(key)
      })
      salvar()
    };

    const loading = ref(false);

    const profissoesOptions = computed(() => {
      const opcoes = [{ value: '', labels: 'Selecione uma profissão' }];
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
        if (dados.complemento) {
          endereco += `, ${dados.complemento}`;
        }
        if (dados.bairro) {
          endereco += ` - ${dados.bairro}`;
        }
        return endereco;
      }
      return '';
    });

    const isEdicao = computed(() => !!route.params.id);

    const isFormValid = computed(() => {
      return form.value.id_profissao &&
        !Object.values(errors.value).some(error => error !== '');
    });

    // Carregar profissões ao montar o componente
    onMounted(async () => {
      if (profissoesOptions.value.length <= 1) {
        await store.dispatch('profissao/fetchProfissoes');
      }
    });

    const validarForm = () => {
      let valid = true;

      // Reset errors
      errors.value.id_profissao = '';

      // Validar profissão
      if (!form.value.id_profissao) {
        errors.value.id_profissao = 'A profissão é obrigatória';
        valid = false;
      }

      return valid;
    };

    const voltar = () => {
      store.dispatch('cliente/updateFormData', form.value);
      store.dispatch('cliente/previousStep');
    };

    const salvar = async () => {
      if (validarForm()) {
        loading.value = true;
        store.dispatch('cliente/updateFormData', form.value);

        try {
          const id = route.params.id;
          let resultado;

          if (id) {
            // Atualizar cliente existente
            resultado = await store.dispatch('cliente/updateCliente', id);
          } else {
            // Criar novo cliente
            resultado = await store.dispatch('cliente/createCliente');
          }

          // Mostrar notificação de sucesso
          showSuccessNotification(
            isEdicao.value ? 'Cliente atualizado com sucesso!' : 'Cliente cadastrado com sucesso!'
          );

          // Limpar formulário e redirecionar para a lista
          store.dispatch('cliente/resetForm');

          // Aguardar um pouco antes de redirecionar para o usuário ver a mensagem
          setTimeout(() => {
            router.push({ name: 'clientes' });
          }, 1500);

        } catch (error) {
          console.error('Erro ao salvar cliente:', error);

          // Se for um erro de validação, exibir mensagens
          if (error.response && error.response.data && error.response.data.errors) {
            const apiErrors = error.response.data.errors;

            // Mapear erros da API para o formulário
            Object.keys(apiErrors).forEach(key => {
              if (errors.value.hasOwnProperty(key)) {
                errors.value[key] = apiErrors[key][0];
              }
            });

            showErrorNotification('Verifique os dados informados e tente novamente.');
          } else {
            showErrorNotification('Erro interno. Tente novamente em alguns instantes.');
          }
        } finally {
          loading.value = false;
        }
      }
    };

    const showSuccessNotification = (message) => {
      // Criar elemento de notificação
      const notification = document.createElement('div');
      notification.className = 'alert alert-success alert-notification';
      notification.innerHTML = `
        <div class="d-flex align-items-center">
          <i class="fas fa-check-circle me-3"></i>
          <span>${message}</span>
        </div>
      `;

      // Adicionar estilos
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        animation: slideInRight 0.3s ease;
      `;

      document.body.appendChild(notification);

      // Remover após 3 segundos
      setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    };

    const showErrorNotification = (message) => {
      // Criar elemento de notificação
      const notification = document.createElement('div');
      notification.className = 'alert alert-danger alert-notification';
      notification.innerHTML = `
        <div class="d-flex align-items-center">
          <i class="fas fa-exclamation-triangle me-3"></i>
          <span>${message}</span>
        </div>
      `;

      // Adicionar estilos
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        animation: slideInRight 0.3s ease;
      `;

      document.body.appendChild(notification);

      // Remover após 5 segundos
      setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 5000);
    };


    watch(
      form,
      (newValue) => {
        const hasValue = Object.values(newValue).some(v => v !== '' && v !== null);
        if (hasValue) {
          store.dispatch('cliente/updateFormData', newValue);
        }
        validarForm();
      },
      { deep: true }
    );


    return {
      form,
      errors,
      loading,
      profissoesOptions,
      dadosResumo,
      enderecoCompleto,
      isEdicao,
      isFormValid,
      voltar,
      handleSalvar,
      salvar
      
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

.info-box {
  background: linear-gradient(135deg, #e3f2fd 0%, #f0f8ff 100%);
  border: 1px solid #bbdefb;
  border-radius: 12px;
  padding: 1rem;
}

.info-header {
  color: #1976d2;
  font-weight: 600;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.info-content {
  color: #424242;
  margin: 0;
  font-size: 0.85rem;
  line-height: 1.5;
}

.resumo-dados {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border: 1px solid #dee2e6;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.resumo-header {
  color: #0a3b25;
  font-weight: 600;
  margin-bottom: 1rem;
  font-size: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.resumo-content {
  background: white;
  border-radius: 8px;
  padding: 1.25rem;
}

.resumo-section {
  margin-bottom: 1rem;
}

.resumo-section-title {
  color: #0a3b25;
  font-weight: 600;
  margin-bottom: 0.75rem;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.resumo-item {
  display: flex;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.resumo-item .label {
  font-weight: 600;
  color: #495057;
  min-width: 80px;
  margin-right: 0.5rem;
}

.resumo-item .value {
  color: #212529;
  flex: 1;
  word-break: break-word;
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

.btn-save {
  position: relative;
  font-size: 1rem;
  font-weight: 600;
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

  .resumo-dados {
    padding: 1rem;
  }

  .resumo-content {
    padding: 1rem;
  }

  .resumo-item {
    flex-direction: column;
    gap: 0.25rem;
  }

  .resumo-item .label {
    min-width: auto;
    margin-right: 0;
  }
}

/* Animações para notificações */
@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }

  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOutRight {
  from {
    transform: translateX(0);
    opacity: 1;
  }

  to {
    transform: translateX(100%);
    opacity: 0;
  }
}
</style>