# 📢 Avalia Ai!

**Avalia Ai!** é uma plataforma de avaliações inspirada no famoso site Reclame Aqui. Aqui, empresas privadas e órgãos públicos podem se cadastrar, divulgar seus produtos e serviços, enquanto usuários têm voz ativa para compartilhar experiências, avaliar e deixar feedbacks sobre os serviços utilizados.

---

## 🚀 Tecnologias Utilizadas

### 🎨 Front-End
- [Vue 3](https://vuejs.org/) – Framework progressivo para interfaces ricas
- [Vue Router](https://router.vuejs.org/) – Navegação SPA
- [Axios](https://axios-http.com/) – Comunicação com a API
- [Tailwind CSS](https://tailwindcss.com/) – Estilização rápida e moderna

### 🔧 Back-End
- [Laravel 12](https://laravel.com/) – Framework robusto para APIs
- [MySQL](https://www.mysql.com/) – Banco de dados relacional
- [Sanctum](https://laravel.com/docs/sanctum) – Autenticação baseada em tokens
- [Redis](https://redis.io/) – Pesquisas com retornos rápidos
- [Mailpit](https://mailpit.axllent.org/) – Captura de e-mails em ambiente de desenvolvimento
- [PhpUnit](https://laravel.com/docs/12.x/testing) – Testes Unitários

### 🐳 DevOps
- [Docker](https://www.docker.com/) – Ambientes isolados em containers


---

## 🛠️ Como Instalar e Rodar o Projeto

### ⚙️ Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

### 🧱 Passo a passo

#### 1. Clone o repositório
```bash
git clone https://github.com/JonasMoria/Avaliai.git
cd avalia-ai
```

#### 2. Suba os containers com Docker Compose
```bash
docker-compose up -d --build
```

#### 3. Instale as dependências (em containers separados)

##### Backend (Laravel)
```bash
docker exec -it avaliai-api bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
exit
```

##### FrontEnd (Vue 3)
```bash
docker exec -it app bash
npm install
exit
```

#### 4. Acesse a aplicação
- Frontend (Vue): http://localhost:5173
- Backend API (Laravel): http://localhost:8000/api
- Mailpit: http://localhost:8025

## 🧪 Testes
```bash
docker exec -it avaliai-api bash
php artisan test
```

## 🔁 Sincronização com Redis (Crontab)

Para manter o Redis atualizado com os dados de empresas e serviços, o sistema utiliza uma fila intermediária (`sync_queue`). Essa tabela recebe entradas sempre que uma empresa ou serviço é criado, atualizado ou deletado, e uma tarefa agendada trata de enviar esses dados ao Redis.

---

### ✅ Execução manual

Você pode rodar a sincronização manualmente com:

```bash
docker exec -it avaliai-api bash
php artisan sync:redis
```
Esse comando:
- Lê até 30 registros pendentes da tabela sync_queue
- Insere, atualiza ou remove os dados no Redis via RedisJSON
- Atualiza o campo synced = true para indicar sucesso

## 📬 Contato e Contribuição
Gostou da ideia? Tem alguma sugestão ou encontrou um bug? Fique à vontade para abrir uma issue ou pull request!