import { watch } from 'vue';

/**
 * Monitora os campos do formulário de endereço e atualiza as mensagens de erro em tempo real:
 * - Para cada campo obrigatório (endereco, numero, bairro, cidade, uf), ao detectar uma mudança,
 *   primeiro limpa a mensagem de erro; se o novo valor estiver vazio (ou só com espaços), 
 *   atribui a mensagem de erro correspondente.
 * - O campo 'complemento' também é observado apenas para limpar qualquer erro existente
 *   sempre que seu valor for alterado.
 */
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

  watch(() => form.value.complemento, () => {
    errors.value.complemento = '';
  });
}
