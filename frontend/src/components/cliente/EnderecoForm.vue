<template>
  <div class="endereco-form">
    <h4>Endereço</h4>
    
    <InputField
      id="endereco"
      label="Endereço (Rua/Av/Pq)"
      v-model="form.endereco"
      :maxlength="255"
      :error="errors.endereco"
      required
    />
    
    <InputField
      id="numero"
      label="Número"
      v-model="form.numero"
      :maxlength="20"
      :error="errors.numero"
      required
    />
    
    <InputField
      id="bairro"
      label="Bairro"
      v-model="form.bairro"
      :maxlength="255"
      :error="errors.bairro"
      required
    />
    
    <InputField
      id="complemento"
      label="Complemento"
      v-model="form.complemento"
      :maxlength="255"
      :error="errors.complemento"
    />
    
    <InputField
      id="cidade"
      label="Cidade"
      v-model="form.cidade"
      :maxlength="255"
      :error="errors.cidade"
      required
    />
    
    <SelectField
      id="uf"
      label="UF"
      v-model="form.uf"
      :options="estados"
      :error="errors.uf"
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
        @click="prosseguir"
      >
        Próximo
      </button>
    </div>
  </div>
</template>

<script>
import { ref, watch } from 'vue';
import { useStore } from 'vuex';
import InputField from '@/components/ui/InputField.vue';
import SelectField from '@/components/ui/SelectField.vue';

export default {
  name: 'EnderecoForm',
  components: {
    InputField,
    SelectField
  },
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
    
    const errors = ref({
      endereco: '',
      numero: '',
      bairro: '',
      complemento: '',
      cidade: '',
      uf: ''
    });
    
    const estados = [
      { value: 'AC', text: 'Acre' },
      { value: 'AL', text: 'Alagoas' },
      { value: 'AP', text: 'Amapá' },
      { value: 'AM', text: 'Amazonas' },
      { value: 'BA', text: 'Bahia' },
      { value: 'CE', text: 'Ceará' },
      { value: 'DF', text: 'Distrito Federal' },
      { value: 'ES', text: 'Espírito Santo' },
      { value: 'GO', text: 'Goiás' },
      { value: 'MA', text: 'Maranhão' },
      { value: 'MT', text: 'Mato Grosso' },
      { value: 'MS', text: 'Mato Grosso do Sul' },
      { value: 'MG', text: 'Minas Gerais' },
      { value: 'PA', text: 'Pará' },
      { value: 'PB', text: 'Paraíba' },
      { value: 'PR', text: 'Paraná' },
      { value: 'PE', text: 'Pernambuco' },
      { value: 'PI', text: 'Piauí' },
      { value: 'RJ', text: 'Rio de Janeiro' },
      { value: 'RN', text: 'Rio Grande do Norte' },
      { value: 'RS', text: 'Rio Grande do Sul' },
      { value: 'RO', text: 'Rondônia' },
      { value: 'RR', text: 'Roraima' },
      { value: 'SC', text: 'Santa Catarina' },
      { value: 'SP', text: 'São Paulo' },
      { value: 'SE', text: 'Sergipe' },
      { value: 'TO', text: 'Tocantins' }
    ];
    
    const validarForm = () => {
      let valid = true;
      
      // Reset errors
      Object.keys(errors.value).forEach(key => {
        errors.value[key] = '';
      });
      
      // Validar endereço
      if (!form.value.endereco.trim()) {
        errors.value.endereco = 'O endereço é obrigatório';
        valid = false;
      }
      
      // Validar número
      if (!form.value.numero.trim()) {
        errors.value.numero = 'O número é obrigatório';
        valid = false;
      }
      
      // Validar bairro
      if (!form.value.bairro.trim()) {
        errors.value.bairro = 'O bairro é obrigatório';
        valid = false;
      }
      
      // Validar cidade
      if (!form.value.cidade.trim()) {
        errors.value.cidade = 'A cidade é obrigatória';
        valid = false;
      }
      
      // Validar UF
      if (!form.value.uf) {
        errors.value.uf = 'A UF é obrigatória';
        valid = false;
      }
      
      return valid;
    };
    
    const voltar = () => {
      store.dispatch('cliente/updateFormData', form.value);
      store.dispatch('cliente/previousStep');
    };
    
    const prosseguir = () => {
      if (validarForm()) {
        store.dispatch('cliente/updateFormData', form.value);
        store.dispatch('cliente/nextStep');
      }
    };
    
    // Atualizar o store quando os dados do formulário mudam
    watch(form, (newValue) => {
      store.dispatch('cliente/updateFormData', newValue);
    }, { deep: true });
    
    return {
      form,
      errors,
      estados,
      voltar,
      prosseguir
    };
  }
};
</script>