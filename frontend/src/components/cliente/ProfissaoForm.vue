<template>
  <div class="profissao-form">
    <h4>Profissão</h4>
    
    <SelectField
      id="id_profissao"
      label="Profissão"
      v-model="form.id_profissao"
      :options="profissoesOptions"
      :error="errors.id_profissao"
      required
    />
    
    <div class="form-actions mt-4">
      <button
        type="button"
        class="btn btn-secondary mr-2"
        @click="voltar"
      >
        Voltar
      </button>
      <button
        type="button"
        class="btn btn-primary"
        @click="salvar"
      >
        Salvar
      </button>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';
import SelectField from '@/components/ui/SelectField.vue';

export default {
  name: 'ProfissaoForm',
  components: {
    SelectField
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    const route = useRoute();
    const formData = store.getters['cliente/getFormData'];
    
    const form = ref({
      id_profissao: formData.id_profissao || ''
    });
    
    const errors = ref({
      id_profissao: ''
    });
    
    const profissoesOptions = computed(() => {
      return store.getters['profissao/getProfissoes'].map(p => ({
        value: p.id,
        text: p.nome_profissao
      }));
    });
    
    // Carregar profissões ao montar o componente
    onMounted(async () => {
      if (profissoesOptions.value.length === 0) {
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
        store.dispatch('cliente/updateFormData', form.value);
        
        try {
          const id = route.params.id;
          
          if (id) {
            // Atualizar cliente existente
            await store.dispatch('cliente/updateCliente', id);
            alert('Cliente atualizado com sucesso!');
          } else {
            // Criar novo cliente
            await store.dispatch('cliente/createCliente');
            alert('Cliente cadastrado com sucesso!');
          }
          
          // Limpar formulário e redirecionar para a lista
          store.dispatch('cliente/resetForm');
          router.push({ name: 'clientes' });
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
          }
        }
      }
    };
    
    // Atualizar o store quando os dados do formulário mudam
    watch(form, (newValue) => {
      store.dispatch('cliente/updateFormData', newValue);
    }, { deep: true });
    
    return {
      form,
      errors,
      profissoesOptions,
      voltar,
      salvar
    };
  }
};
</script>