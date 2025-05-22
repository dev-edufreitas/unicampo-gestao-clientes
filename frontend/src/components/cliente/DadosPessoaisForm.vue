<template>
  <div class="dados-pessoais-form">
    <h4>Dados Pessoais</h4>
    
    <InputField
      id="nome"
      label="Nome"
      v-model="form.nome"
      :maxlength="255"
      :error="errors.nome"
      required
    />
    
    <InputField
      id="data_nascimento"
      label="Data de Nascimento"
      type="date"
      v-model="form.data_nascimento"
      :error="errors.data_nascimento"
      required
    />
    
    <SelectField
      id="tipo_pessoa"
      label="Tipo de Pessoa"
      v-model="form.tipo_pessoa"
      :options="tiposPessoa"
      :error="errors.tipo_pessoa"
      required
      @update:modelValue="resetDocumento"
    />
    
    <InputField
      id="documento"
      :label="documentoLabel"
      v-model="form.documento"
      :maxlength="form.tipo_pessoa === 'fisica' ? 14 : 18"
      :error="errors.documento"
      required
      v-mask="form.tipo_pessoa === 'fisica' ? '###.###.###-##' : '##.###.###/####-##'"
    />
    
    <InputField
      id="email"
      label="Email"
      type="email"
      v-model="form.email"
      :error="errors.email"
      required
    />
    
    <InputField
      id="telefone"
      label="Telefone"
      v-model="form.telefone"
      :error="errors.telefone"
      required
      v-mask="'(##) #####-####'"
    />
      <div class="form-actions mt-4">
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
import { ref, computed, watch } from 'vue';
import { useStore } from 'vuex';
import InputField from '@/components/ui/InputField.vue';
import SelectField from '@/components/ui/SelectField.vue';
import { mask } from 'vue-the-mask';
import { validaCPF, validaCNPJ, validaEmail } from '@/utils/validators';

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
    
    const form = ref({
      nome: formData.nome || '',
      data_nascimento: formData.data_nascimento || '',
      tipo_pessoa: formData.tipo_pessoa || 'fisica',
      documento: formData.documento || '',
      email: formData.email || '',
      telefone: formData.telefone || ''
    });
    
    const errors = ref({
      nome: '',
      data_nascimento: '',
      tipo_pessoa: '',
      documento: '',
      email: '',
      telefone: ''
    });
    
    const tiposPessoa = [
      { value: 'fisica', text: 'Pessoa Física' },
      { value: 'juridica', text: 'Pessoa Jurídica' }
    ];
    
    const documentoLabel = computed(() => {
      return form.value.tipo_pessoa === 'fisica' ? 'CPF' : 'CNPJ';
    });
    
    const resetDocumento = () => {
      form.value.documento = '';
      errors.value.documento = '';
    };
    
    const validarForm = () => {
      let valid = true;
      
      // Reset errors
      Object.keys(errors.value).forEach(key => {
        errors.value[key] = '';
      });
      
      // Validar nome
      if (!form.value.nome.trim()) {
        errors.value.nome = 'O nome é obrigatório';
        valid = false;
      } else if (form.value.nome.length > 255) {
        errors.value.nome = 'O nome deve ter no máximo 255 caracteres';
        valid = false;
      }
      
      // Validar data de nascimento
      if (!form.value.data_nascimento) {
        errors.value.data_nascimento = 'A data de nascimento é obrigatória';
        valid = false;
      }
      
      // Validar tipo de pessoa
      if (!form.value.tipo_pessoa) {
        errors.value.tipo_pessoa = 'O tipo de pessoa é obrigatório';
        valid = false;
      }
      
      // Validar documento (CPF/CNPJ)
      if (!form.value.documento) {
        errors.value.documento = `O ${documentoLabel.value} é obrigatório`;
        valid = false;
      } else {
        const doc = form.value.documento.replace(/[^\d]/g, '');
        
        if (form.value.tipo_pessoa === 'fisica') {
          if (!validaCPF(doc)) {
            errors.value.documento = 'CPF inválido';
            valid = false;
          }
        } else {
          if (!validaCNPJ(doc)) {
            errors.value.documento = 'CNPJ inválido';
            valid = false;
          }
        }
      }
      
      // Validar email
      if (!form.value.email) {
        errors.value.email = 'O email é obrigatório';
        valid = false;
      } else if (!validaEmail(form.value.email)) {
        errors.value.email = 'Email inválido';
        valid = false;
      }
      
      // Validar telefone
      if (!form.value.telefone) {
        errors.value.telefone = 'O telefone é obrigatório';
        valid = false;
      } else {
        const tel = form.value.telefone.replace(/[^\d]/g, '');
        if (tel.length < 10 || tel.length > 11) {
          errors.value.telefone = 'O telefone deve conter DDD + 8 ou 9 dígitos';
          valid = false;
        }
      }
      
      return valid;
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
      tiposPessoa,
      documentoLabel,
      resetDocumento,
      prosseguir
    };
  }
};
</script>