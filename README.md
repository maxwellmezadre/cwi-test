# cwi-test — Guia rápido (PT‑BR)

API simples em **Laravel** com **MySQL**, **Passport (M2M / Client Credentials)** e um mock **Node (Express)**.

> **Importante:** em **todas** as chamadas envie o header  
> `Accept: application/json`.  
> Sem ele, o Laravel pode retornar HTML.

---

## Pré‑requisitos
- Docker + Docker Compose
- Git
- **Composer** instalado localmente

---

## 1) Clonar e instalar dependências

```bash
git clone https://github.com/maxwellmezadre/cwi-test.git
cd cwi-test

# copiar variáveis
cp .env.example .env

# instalar dependências
composer install

# gerar chave da aplicação
php artisan key:generate
```

> Se você não tiver PHP local, pode gerar a chave depois que subir o Sail usando:  
> `./vendor/bin/sail artisan key:generate`

---

## 2) Subir os serviços e preparar o banco

```bash
# subir containers (app, nginx, mysql, node mock)
./vendor/bin/sail up -d

# rodar migrações
./vendor/bin/sail artisan migrate
```

---

## 3) Passport (client_credentials)

```bash
# criar um client M2M
./vendor/bin/sail artisan passport:client --client --name="cwi"
```

Guarde o **client_id** e o **client_secret**.

> Base URL padrão: `http://localhost`  
> Se mapeou porta (APP_PORT=8000), use `http://localhost:8000`.

---

## 4) Gerar token (client_credentials)

**Endpoint:** `POST /oauth/token`  
**Headers:**  
- `Accept: application/json`  
- `Content-Type: application/x-www-form-urlencoded`

**Scopes disponíveis:**
- `users.read` — listar/ver usuários
- `users.write` — criar/editar/excluir usuários
- `external.read` — chamar `/api/external`

```bash
curl -X POST http://localhost/oauth/token \
  -H "Accept: application/json" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "grant_type=client_credentials&client_id=SEU_CLIENT_ID&client_secret=SEU_CLIENT_SECRET&scope=users.read users.write external.read"
```

Copie o `access_token` da resposta.

---

## 5) Consumir a API (`/api`)

> Sempre envie: `Accept: application/json`  
> Autenticação: `Authorization: Bearer <access_token>`

```bash
TOKEN="COLE_AQUI_O_ACCESS_TOKEN"
```

### Users (requer scopes)
- **Listar** (users.read)
```bash
curl http://localhost/api/users \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

- **Criar** (users.write)
```bash
curl -X POST http://localhost/api/users \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"Max","email":"max@teste.com","password":"secret123"}'
```

- **Mostrar** (users.read)
```bash
curl http://localhost/api/users/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

- **Atualizar** (users.write)
```bash
curl -X PUT http://localhost/api/users/1 \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"Max Atualizado"}'
```

- **Excluir** (users.write)
```bash
curl -X DELETE http://localhost/api/users/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### External (Node mock)
- **Chamar** (external.read)  
  Retorna o JSON do mock em `http://localhost:3001/ping`.
```bash
curl http://localhost/api/external \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

---

## 6) Testes (Pest)

Rodar:
```bash
./vendor/bin/sail pest
```

---
