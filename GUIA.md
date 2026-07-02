# Guia de Instalação e Execução — Projeto Wallet

Este projeto é **autocontido no Docker**: numa máquina nova, o **único** pré-requisito é ter o **Docker** instalado. Não é necessário instalar PHP, Composer ou Node.js no computador — tudo é construído e executado dentro dos containers.

Stack do projeto (**TALL stack**):

- **T**ailwind CSS v4 (via `@tailwindcss/vite`)
- **A**lpine.js (embutido no Livewire 4)
- **L**aravel 13 (PHP 8.5)
- **L**ivewire v4

Banco de dados: **PostgreSQL 18**.

---

## 1. Pré-requisito único

- **Docker Desktop** (Windows/macOS) ou **Docker Engine + Docker Compose** (Linux).
  - No Windows, ative a integração com **WSL2** no Docker Desktop.

Confirme que está instalado:

```bash
docker -v
docker compose version
```

---

## 2. Rodar o projeto (uma única linha)

Na raiz do projeto:

```bash
docker compose up --build
```

Isso faz **tudo automaticamente**:

1. Constrói a imagem da aplicação (instala PHP + extensões, dependências do Composer e compila os assets do Tailwind/Vite).
2. Sobe o banco **PostgreSQL** e aguarda ele ficar saudável.
3. Gera a `APP_KEY` (se necessário), roda as **migrations** e cria o link de storage.
4. Inicia o servidor da aplicação.

Quando aparecer `Iniciando o servidor em http://0.0.0.0:8000`, acesse:

- **Aplicação:** http://localhost:8000

Para rodar em segundo plano (modo destacado):

```bash
docker compose up --build -d
```

Para parar:

```bash
docker compose down
```

> É isso. Não há passos manuais de `composer install`, `npm install` ou `php artisan` no host.

---

## 3. Como funciona (visão geral)

| Arquivo | Papel |
|---------|-------|
| `Dockerfile` | Build multi-stage: (1) compila assets com Node, (2) instala dependências com Composer, (3) imagem final PHP 8.5 com tudo embutido. |
| `docker/entrypoint.sh` | Prepara `.env`/chave, espera o Postgres, roda migrations e sobe o servidor. |
| `compose.yaml` | Orquestra os serviços `app` (aplicação) e `pgsql` (banco). |
| `.dockerignore` | Mantém o build limpo e rápido (ignora `vendor`, `node_modules`, `.env`, etc.). |

Serviços definidos no `compose.yaml`:

- **`app`** — a aplicação Laravel, exposta na porta `8000`.
- **`pgsql`** — PostgreSQL 18, com dados persistidos no volume `sail-pgsql`.

---

## 4. Configuração (opcional)

O projeto roda com valores padrão sem nenhuma configuração. Para customizar, crie um arquivo `.env` na raiz — o `compose.yaml` lê estas variáveis (todas têm padrão):

| Variável | Padrão | Descrição |
|----------|--------|-----------|
| `APP_PORT` | `8000` | Porta da aplicação no host. |
| `APP_KEY` | (chave de dev embutida) | Defina uma própria em produção. |
| `APP_DEBUG` | `true` | Use `false` em produção. |
| `DB_DATABASE` | `laravel` | Nome do banco. |
| `DB_USERNAME` | `sail` | Usuário do banco. |
| `DB_PASSWORD` | `password` | Senha do banco. |
| `FORWARD_DB_PORT` | `5432` | Porta do Postgres exposta no host. |

Exemplo de `.env` para trocar a porta e o banco:

```env
APP_PORT=8080
DB_DATABASE=wallet
DB_PASSWORD=umaSenhaForte
```

Depois: `docker compose up --build -d`.

> Para acessar o banco de fora (DBeaver, TablePlus): host `localhost`, porta `5432` (ou `FORWARD_DB_PORT`), usuário/senha/banco conforme acima.

---

## 5. Comandos do dia a dia

Todos os comandos rodam **dentro do container**, sem precisar de nada no host:

| Ação | Comando |
|------|---------|
| Subir (background) | `docker compose up -d` |
| Subir reconstruindo a imagem | `docker compose up --build -d` |
| Parar | `docker compose down` |
| Parar e apagar o banco | `docker compose down -v` |
| Ver logs | `docker compose logs -f app` |
| Rodar migrations | `docker compose exec app php artisan migrate` |
| Recriar o banco | `docker compose exec app php artisan migrate:fresh` |
| Tinker (REPL) | `docker compose exec app php artisan tinker` |
| Rodar testes | `docker compose exec app php artisan test` |
| Abrir shell no container | `docker compose exec app bash` |
| Rodar um artisan qualquer | `docker compose run --rm app php artisan <comando>` |

---

## 6. Modo de desenvolvimento (live-reload)

Para desenvolver com **recarregamento automático**, use o override `compose.dev.yaml`:

```bash
docker compose -f compose.yaml -f compose.dev.yaml up --build
```

O que muda no modo dev:

- **Código do host montado no container** — alterações em arquivos **PHP/Blade** refletem na hora (o `artisan serve` lê os arquivos a cada requisição), sem rebuild.
- **Servidor Vite com HMR** — um serviço `vite` sobe em http://localhost:5173 e faz **hot reload** de CSS/JS/Blade. Enquanto ele estiver rodando, a aplicação usa os assets do Vite automaticamente.
- **Dependências de dev incluídas** — a imagem usa o stage `dev` (Composer + Faker, PHPUnit, Pint, etc.), permitindo rodar testes e ferramentas.
- **Sem tooling no host** — `vendor` e `node_modules` ficam em volumes gerenciados pelo Docker (nada é instalado na sua máquina; também evita a lentidão de bind mount no Windows/macOS).

Acessos no modo dev:

- **Aplicação:** http://localhost:8000
- **Vite (HMR):** http://localhost:5173

Para parar: `docker compose -f compose.yaml -f compose.dev.yaml down`.

> Dica: crie um alias para não digitar as duas flags sempre. Ex. (PowerShell): `function dcdev { docker compose -f compose.yaml -f compose.dev.yaml @args }` e use `dcdev up`.

### Produção vs. desenvolvimento

| | Produção (padrão) | Desenvolvimento |
|---|---|---|
| Comando | `docker compose up --build` | `docker compose -f compose.yaml -f compose.dev.yaml up --build` |
| Assets | compilados na imagem (`npm run build`) | servidos pelo Vite com HMR |
| Código | copiado para a imagem | montado do host (live-reload) |
| Dependências | `--no-dev` | inclui dependências de dev |

> Rebuilds são incrementais e usam cache do Docker — só as camadas afetadas são reconstruídas.

---

## 7. Solução de problemas

- **Porta 8000 ocupada:** defina `APP_PORT=8080` no `.env` e suba de novo.
- **Porta 5432 ocupada** (já há Postgres local): defina `FORWARD_DB_PORT=5433` no `.env`.
- **Porta 5173 ocupada** (outro projeto com Vite): defina `VITE_PORT=5174` no `.env` (o HMR se ajusta automaticamente).
- **App sobe antes do banco:** já tratado — o serviço `app` espera o `pgsql` ficar *healthy* e o entrypoint aguarda a conexão.
- **Quero zerar tudo:** `docker compose down -v` remove os containers e o volume do banco. O próximo `up` recria do zero.
- **Alterações não aparecem:** reconstrua com `docker compose up --build`.
- **Ver o que está rodando:** `docker compose ps`.
