import { watch } from 'vue';
import { validaCPF, validaCNPJ, validaEmail } from '@/utils/validators';

/**
 * Monitora e valida os campos do formulário de cliente, atualizando mensagens de erro.
 */
export function useValidacaoCliente(form, errors, documentoLabel) {
  // Nome: obrigatório e até 255 caracteres
  watch(() => form.value.nome, (val) => {
    errors.value.nome = '';
    if (!val.trim()) errors.value.nome = 'O nome é obrigatório';
    else if (val.length > 255) errors.value.nome = 'O nome deve ter no máximo 255 caracteres';
  });

  // Data de nascimento: obrigatório e anterior à data atual
  watch(() => form.value.data_nascimento, (val) => {
    errors.value.data_nascimento = '';
    if (!val) {
      errors.value.data_nascimento = 'A data de nascimento é obrigatória';
    } else {
      const hoje = new Date();
      const nascimento = new Date(val);
      if (nascimento >= hoje) {
        errors.value.data_nascimento = 'A data de nascimento deve ser anterior à data atual';
      }
    }
  });

  // Tipo de pessoa: obrigatório
  watch(() => form.value.tipo_pessoa, (val) => {
    errors.value.tipo_pessoa = '';
    if (!val) {
      errors.value.tipo_pessoa = 'O tipo de pessoa é obrigatório';
    }
  });

  // Documento: obrigatório; valida CPF para pessoa física e CNPJ para jurídica
  watch(() => form.value.documento, (val) => {
    errors.value.documento = '';
    const doc = val.replace(/[^\d]/g, '');
    if (!doc) {
      errors.value.documento = `O ${documentoLabel.value} é obrigatório`;
    } else if (form.value.tipo_pessoa === 'fisica' && !validaCPF(doc)) {
      errors.value.documento = 'CPF inválido';
    } else if (form.value.tipo_pessoa === 'juridica' && !validaCNPJ(doc)) {
      errors.value.documento = 'CNPJ inválido';
    }
  });

  // Email: obrigatório e formato válido
   watch(() => form.value.email, (val) => {
    errors.value.email = '';

    if (!val || !val.trim()) {
      errors.value.email = 'O email é obrigatório';
      return;
    }

    const emailLimpo = val.trim();
    
    if (!validaEmail(emailLimpo)) {
      errors.value.email = 'Email inválido';
    }
  });
}
