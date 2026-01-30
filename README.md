# 🚀 Teste Técnico - Desenvolvedor PHP (Laravel) - First Decision

## 📌 Descrição do Projeto

Este projeto é uma aplicação web completa para gerenciamento de produtos, desenvolvida com tecnologias modernas e boas práticas de mercado.

* **Backend**: Laravel (API REST)
* **Frontend**: Vue.js 3 + PrimeVue (SPA)
* **Banco de Dados**: PostgreSQL
* **Infraestrutura**: Docker e Docker Compose

O objetivo é fornecer um ambiente padronizado, simples de subir e fácil de avaliar tecnicamente.

---

## 📁 Estrutura do Projeto

```bash
.
├── backend/        # API Laravel
├── frontend/       # SPA Vue.js
├── docker/         # Arquivos de infraestrutura (PHP, Nginx, Frontend)
├── docker-compose.yml
└── README.md

## ▶️ Como Executar a Aplicação

### 1️⃣ Clonar o repositório

```bash
git clone git@github.com:daniellandim/teste-tecnico-php-first-decision.git
cd teste-tecnico-php-first-decision
```

---

### 2️⃣ Subir os containers com Docker

Certifique-se de ter o Docker e o Docker Compose instalados.

```bash
docker compose up -d --build
```

Esse comando irá subir os seguintes serviços:
* **app**: Laravel (API)
* **nginx**: Servidor web para a API
* **frontend**: Aplicação Vue.js (Vite)
* **db**: Banco de dados PostgreSQL

---

### 3️⃣ Configurar o Backend (Laravel)

Após os containers estarem rodando, execute os comandos abaixo para configurar a aplicação:

1. Instalar dependências do PHP (caso não tenham sido instaladas no build):
```bash
docker compose exec app composer install
```

2. Gerar a chave da aplicação:
```bash
docker compose exec app php artisan key:generate
```

3. Executar as migrações e popular o banco de dados:
```bash
docker compose exec app php artisan migrate --seed
```

---

### 4️⃣ Configurar o Frontend (Vue.js)

Caso as dependências do frontend não tenham sido instaladas automaticamente:

```bash
docker compose exec frontend npm install
```

---

## 🌐 Acessos da Aplicação

* **Frontend (Aplicação Web)**: [http://localhost:5173](http://localhost:5173)
* **Backend (API Documentation/Endpoint)**: [http://localhost:8000](http://localhost:8000)

---

## 🔐 Credenciais de Acesso

Um usuário administrador é criado automaticamente via *seed* para facilitar os testes:

- **E-mail:** `test@example.com`
- **Senha:** `password`

---

## 🧪 Como Executar os Testes

Para rodar os testes automatizados (Feature/Unit) do Laravel:

```bash
docker compose exec app php artisan test
```

---

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 12+**: Framework PHP robusto.
- **PostgreSQL**: Banco de dados relacional.
- **PHPUnit**: Testes automatizados.

### Frontend
- **Vue.js 3**: Framework JavaScript progressivo (Composition API).
- **Vite**: Build tool rápida e moderna.
- **PrimeVue**: Biblioteca de componentes de UI rica e responsiva.
- **Pinia**: Gerenciamento de estado intuitivo.
- **Axios**: Cliente HTTP para comunicação com a API.
- **VeeValidate**: Validação de formulários.

---

## 🖼️ Funcionalidades

* **Autenticação JWT**: Login seguro com token.
* **CRUD de Produtos**:
    * Listagem com paginação (se aplicável).
    * Cadastro de novos produtos.
    * Edição de informações.
    * Exclusão de registros.
* **Layout Responsivo**: Interface adaptável para diferentes dispositivos.
* **Feedback Visual**: Notificações de sucesso/erro (Toasts) e indicadores de carregamento.

---

## ℹ️ Observações

* O frontend e o backend rodam em containers separados na mesma rede Docker.
* O banco de dados utiliza volume persistente para não perder dados ao reiniciar os containers.
