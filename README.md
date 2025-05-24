# Sistema de Gerenciamento de Clientes - UNICAMPO

<table align="center">
  <tr>
    <td>
      <img src="https://github.com/user-attachments/assets/d3c7aada-abb2-4308-8636-e65c71ce106b" 
           alt="SistemaUnicampo" 
           height="150" />
    </td>
    <td>
      <img src="https://github.com/user-attachments/assets/294630a4-fe65-4cb6-a7dc-13e341842f47" 
           alt="SistemaUnicampo2" 
           height="150" />
    </td>
    <td>
      <img src="https://github.com/user-attachments/assets/f5d1076e-1340-4691-aeb2-48578b14cfea" 
           alt="CadastroSistemaUnicampo" 
           height="150" />
    </td>
    <td>
      <img src="https://github.com/user-attachments/assets/62989495-2eb4-49c0-9002-e297db0b8b5d" 
           alt="DocumentacaoSwagger" 
           height="150" />
    </td>
  </tr>
</table>

Este projeto é um teste prático para a Unicampo, que implementa um sistema completo de gerenciamento de clientes com interface moderna, validações avançadas e uma API RESTful documentada.

---

## 🚀 Como Executar o Projeto

### Pré-requisitos
- Docker instalado
- Git instalado

### Passos

```bash
git clone https://github.com/dev-edufreitas/unicampo-client-manager.git
cd unicampo-client-manager
docker-compose up -d --build
```

Aguarde a inicialização completa dos serviços. O processo pode levar alguns minutos na primeira execução.

### Acesse a Aplicação

- **Frontend (Vue.js):** [http://localhost:8080](http://localhost:8080)
- **Backend API (Laravel):** [http://localhost:8000](http://localhost:8000)
- **Documentação da API (Swagger):** [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

---

## ✅ Funcionalidades

- Cadastro de clientes (Pessoa Física ou Jurídica)
- Listagem e busca de clientes
- Edição de dados dos clientes
- Validação automática de CPF/CNPJ
- Inativação de clientes
- Dashboard com estatísticas dos clientes
- API RESTful completa e documentada
- Testes automatizados (unitários e de integração)

---

## 🧪 Testes do Backend (Laravel)

Execute os testes com os comandos abaixo:

```bash
# Todos os testes
docker-compose exec backend php artisan test

# Usando script do Composer
docker-compose exec backend composer test

# Testes unitários
docker-compose exec backend php artisan test --testsuite=Unit

# Testes de feature
docker-compose exec backend php artisan test --testsuite=Feature
```

---

## 🛠 Tecnologias Utilizadas

### Frontend
- Vue.js 3
- Vue Router
- Vuex
- Bootstrap 5
- Axios

### Backend
- Laravel 12
- MySQL 8
- Swagger/OpenAPI
- PHPUnit

### DevOps
- Docker

---

## 📁 Estrutura do Projeto

```
unicampo-client-manager/
│
├── 🐳 docker-compose.yml          
├── 📄 README.md
│
├── 🔧 backend/ (Laravel API)
│   │
│   ├── 📂 app/
│   │   ├── Http/Controllers/API/
│   │   │   ├── ClienteController.php     
│   │   │   ├── ProfissaoController.php   
│   │   │   └── EnderecoController.php    
│   │   │
│   │   ├── Models/
│   │   │   ├── Cliente.php               
│   │   │   ├── Profissao.php
│   │   │   └── Endereco.php
│   │   │
│   │   ├── Services/
│   │   │   └── ClienteService.php        
│   │   │
│   │   └── Utils/
│   │       └── DocumentoValidator.php    
│   │
│   ├── 📂 database/
│   │   ├── migrations/                   
│   │   └── seeders/                      
│   │
│   ├── 📂 tests/
│   │   ├── Unit/                         
│   │   └── Feature/                     
│   │
│   └── 🔧 routes/api.php                
│
└── 🎨 frontend/ (Vue.js)
    │
    ├── 📂 src/
    │   ├── components/
    │   │   ├── cliente/                  
    │   │   │   ├── DadosPessoaisForm.vue
    │   │   │   ├── EnderecoForm.vue
    │   │   │   └── ProfissaoForm.vue
    │   │   │
    │   │   └── ui/                       
    │   │       ├── InputField.vue
    │   │       └── SelectField.vue
    │   │
    │   ├── views/                        
    │   │   ├── Home.vue
    │   │   ├── ClienteList.vue
    │   │   └── ClienteForm.vue
    │   │
    │   ├── store/modules/               
    │   │   ├── cliente.js
    │   │   └── profissao.js
    │   │
    │   ├── services/                
    │   │   ├── clienteService.js
    │   │   └── profissaoService.js
    │   │
    │   └── router/index.js           
    │
    └── 🔧 package.json
```

---

## 🔰 Primeiro Acesso

1. Acesse [http://localhost:8080](http://localhost:8080)
2. O sistema já vem com dados de exemplo (profissões e alguns clientes)
3. Explore as funcionalidades pelo menu 
4. Consulte a documentação da API: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

---

## 🧩 Solução de Problemas

- **Portas ocupadas:** Verifique se as portas 8000, 8080 e 3306 estão livres.
- **Erro ao conectar no banco:** Aguarde alguns minutos após subir os containers.
- **Testes falhando:** Confirme se o banco de dados está ativo antes de rodar os testes.
