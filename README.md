# TargetIT API

API desenvolvida em Laravel para gerenciamento de usuários e endereços, com autenticação JWT e controle de permissões baseado em roles.

---

## 🛠️ Tecnologias Utilizadas

- PHP 8+
- Laravel
- JWT (tymon/jwt-auth)
- MySQL

---

## 📦 Requisitos

- PHP 8.3 ou superior
- Composer
- MySQL
- Git

---

## 🚀 Instalação

Clone o repositório:

```bash
git clone https://github.com/seu-usuario/targetit.git
cd targetit
```

Instale as dependências:

```bash
composer install
```

Crie o arquivo de ambiente e Gere as chaves da Aplicação e JWT:

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Execute as migrations:

```bash
php artisan migrate
```

Executando o projeto:

```bash
php artisan serve
```


