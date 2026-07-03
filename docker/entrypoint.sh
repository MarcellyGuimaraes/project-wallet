#!/usr/bin/env bash
set -e

cd /var/www/html

# ------------------------------------------------------------
# 1. Garante um arquivo .env (as variáveis reais vêm do compose)
# ------------------------------------------------------------
if [ ! -f .env ]; then
    cp .env.example .env
fi

# ------------------------------------------------------------
# 2. Define a APP_KEY: usa a do ambiente (persistindo no .env) ou gera uma
# ------------------------------------------------------------
if [ -n "${APP_KEY:-}" ]; then
    # Grava a chave vinda do ambiente no .env (fonte da verdade do Laravel)
    if grep -q "^APP_KEY=" .env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
    else
        echo "APP_KEY=${APP_KEY}" >> .env
    fi
elif ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# ------------------------------------------------------------
# 3. Aguarda o banco de dados ficar disponível
# ------------------------------------------------------------
if [ -n "${DB_HOST:-}" ]; then
    echo "Aguardando o Postgres em ${DB_HOST}:${DB_PORT:-5432}..."
    until pg_isready -h "${DB_HOST}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-sail}" -q; do
        sleep 1
    done
    echo "Postgres disponível."
fi

# ------------------------------------------------------------
# 4. Comandos disponíveis
# ------------------------------------------------------------
case "$1" in
    serve)
        # Otimiza autoload/descoberta de pacotes e roda migrations
        php artisan package:discover --ansi || true
        php artisan migrate --force
        # Seeder idempotente (garante o usuário admin em máquinas novas)
        php artisan db:seed --force || true
        php artisan storage:link || true

        echo "Iniciando o servidor em http://0.0.0.0:8000"
        exec php artisan serve --host=0.0.0.0 --port=8000
        ;;
    queue)
        exec php artisan queue:work --tries=3 --timeout=90
        ;;
    *)
        # Qualquer outro comando é executado diretamente
        exec "$@"
        ;;
esac
