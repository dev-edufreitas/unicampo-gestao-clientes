import { watch } from 'vue';

export function useValidacaoEndereco(form, errors) {
  watch(() => form.value.endereco, (val) => {
    errors.value.endereco = '';
    if (!val.trim()) errors.value.endereco = 'O endereço é obrigatório';
  });

  watch(() => form.value.numero, (val) => {
    errors.value.numero = '';
    if (!val.trim()) errors.value.numero = 'O número é obrigatório';
  });

  watch(() => form.value.bairro, (val) => {
    errors.value.bairro = '';
    if (!val.trim()) errors.value.bairro = 'O bairro é obrigatório';
  });

  watch(() => form.value.cidade, (val) => {
    errors.value.cidade = '';
    if (!val.trim()) errors.value.cidade = 'A cidade é obrigatória';
  });

  watch(() => form.value.uf, (val) => {
    errors.value.uf = '';
    if (!val) errors.value.uf = 'O estado é obrigatório';
  });

  // Complemento é opcional, mas zera erro se houver algum
  watch(() => form.value.complemento, () => {
    errors.value.complemento = '';
  });
}
