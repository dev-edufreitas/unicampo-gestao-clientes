/**
 * Valida um CPF
 * @param {string} cpf - CPF a ser validado (apenas números)
 * @returns {boolean} - True se o CPF for válido, false caso contrário
 */
export function validaCPF(cpf) {
  // Remove os caracteres não numéricos
  cpf = cpf.replace(/[^\d]/g, '');
  
  // Verifica se o CPF tem 11 dígitos
  if (cpf.length !== 11) {
    return false;
  }
  
  // Verifica se todos os dígitos são iguais
  if (/^(\d)\1+$/.test(cpf)) {
    return false;
  }
  
  // Calcula o primeiro dígito verificador
  let soma = 0;
  for (let i = 0; i < 9; i++) {
    soma += parseInt(cpf.charAt(i)) * (10 - i);
  }
  let resto = soma % 11;
  let dv1 = (resto < 2) ? 0 : 11 - resto;
  
  // Calcula o segundo dígito verificador
  soma = 0;
  for (let i = 0; i < 10; i++) {
    soma += parseInt(cpf.charAt(i)) * (11 - i);
  }
  resto = soma % 11;
  let dv2 = (resto < 2) ? 0 : 11 - resto;
  
  // Verifica se os dígitos verificadores estão corretos
  return (parseInt(cpf.charAt(9)) === dv1 && parseInt(cpf.charAt(10)) === dv2);
}

/**
 * Valida um CNPJ
 * @param {string} cnpj - CNPJ a ser validado (apenas números)
 * @returns {boolean} - True se o CNPJ for válido, false caso contrário
 */
export function validaCNPJ(cnpj) {
  // Remove os caracteres não numéricos
  cnpj = cnpj.replace(/[^\d]/g, '');
  
  // Verifica se o CNPJ tem 14 dígitos
  if (cnpj.length !== 14) {
    return false;
  }
  
  // Verifica se todos os dígitos são iguais
  if (/^(\d)\1+$/.test(cnpj)) {
    return false;
  }
  
  // Calcula o primeiro dígito verificador
  let tamanho = cnpj.length - 2;
  let numeros = cnpj.substring(0, tamanho);
  let digitos = cnpj.substring(tamanho);
  let soma = 0;
  let pos = tamanho - 7;
  
  for (let i = tamanho; i >= 1; i--) {
    soma += parseInt(numeros.charAt(tamanho - i)) * pos--;
    if (pos < 2) {
      pos = 9;
    }
  }
  
  let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if (resultado !== parseInt(digitos.charAt(0))) {
    return false;
  }
  
  // Calcula o segundo dígito verificador
  tamanho = tamanho + 1;
  numeros = cnpj.substring(0, tamanho);
  soma = 0;
  pos = tamanho - 7;
  
  for (let i = tamanho; i >= 1; i--) {
    soma += parseInt(numeros.charAt(tamanho - i)) * pos--;
    if (pos < 2) {
      pos = 9;
    }
  }
  
  resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if (resultado !== parseInt(digitos.charAt(1))) {
    return false;
  }
  
  return true;
}

/**
 * Valida um endereço de e-mail
 * @param {string} email - E-mail a ser validado
 * @returns {boolean} - True se o e-mail for válido, false caso contrário
 */
export function validaEmail(email) {
  const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return regex.test(email);
}