# Sistema de Gerenciamento de Clientes - UNICAMPO

Este é um sistema simples de gerenciamento de clientes desenvolvido como parte da avaliação técnica para a vaga de Desenvolvedor Web na empresa UNICAMPO.

## Funcionalidades

- Cadastro de clientes com validações
- Formulário em formato de wizard (passo a passo)
- Filtragem de clientes por status
- Ordenação de clientes por data de criação
- Busca de clientes por nome
- Validações completas de formulários com mensagens de erro
- Recuperação de dados do formulário em caso de reload da página

## Tecnologias Utilizadas

### Backend

- Laravel 10.x
- MySQL
- Docker
- Swagger para documentação da API (L5-Swagger)

### Frontend

- Vue.js 3
- Vuex para gerenciamento de estado
- Vue Router para roteamento
- Axios para requisições HTTP
- Bootstrap 4 para estilização
- FontAwesome para ícones
- Vue-the-mask para máscaras de input

## Pré-requisitos

- Docker e Docker Compose

## Instalação e Execução

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/unicampo-client-manager.git
cd unicampo-client-manager