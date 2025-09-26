# cwi-test — Guia rápido

API simples em **Laravel** com **MySQL**, **Passport (M2M / Client Credentials)** e um mock **Node (Express)**.

> **Importante:** em **todas** as chamadas envie o header  
> `Accept: application/json`.  
> Sem ele, o Laravel pode retornar HTML.

---

## Pré‑requisitos
- Docker + Docker Compose
- Git
- cURL ou Postman/Insomnia

---

## Como rodar

1) **Subir os containers**
```bash
./vendor/bin/sail up -d
```

2) **Rodar as migrações**
```bash
./vendor/bin/sail artisan migrate
```

3) **Criar um client M2M (Client Credentials)**
```bash
./vendor/bin/sail artisan passport:client --client --name="cwi"
```
Anote o **client_id** e o **client_secret**.

> Base URL padrão (Sail + Nginx): `http://localhost`  
> Se mapeou porta (ex.: `8000:80`), use `http://localhost:8000`.

---

## Gerar o access token (client_credentials)

**Endpoint:** `POST /oauth/token`  
**Headers:**  
- `Accept: application/json`  
- `Content-Type: application/x-www-form-urlencoded`

**Scopes:**
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

## Usar o token nas rotas `/api`

> Envie sempre: `Accept: application/json`  
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
  -d '{"name":"Max","email":"max@test.com","password":"password"}'
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
  Retorna o JSON do mock em `http://node-mock:3001/ping`.
```bash
curl http://localhost/api/external \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

---

## Testes (Pest)

Instalar (se necessário):
```bash
./vendor/bin/sail composer require --dev pestphp/pest pestphp/pest-plugin-laravel
./vendor/bin/sail artisan pest:install
```

Rodar:
```bash
./vendor/bin/sail pest
```

---

## Serviços úteis

- App (Laravel): `http://localhost` (ou `http://localhost:8000` se mapeou `8000:80`)
- Node mock (Express): `http://localhost:3001/ping`
- MySQL: `localhost:3306`

Ver portas:
```bash
docker ps --format "table {{.Names}}\t{{.Ports}}"
```
---
