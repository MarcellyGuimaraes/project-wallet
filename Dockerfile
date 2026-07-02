# syntax=docker/dockerfile:1

# ============================================================
# Stage 1 — Build dos assets front-end (Tailwind / Vite)
# ============================================================
FROM node:22-alpine AS assets
WORKDIR /app

# .npmrc do projeto usa ignore-scripts=true, então instalamos e buildamos manualmente
COPY package.json .npmrc ./
RUN npm install --no-audit --no-fund

# Copiamos apenas o necessário para o build do Vite
COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

# ============================================================
# Stage 2 — Dependências PHP (Composer)
# ============================================================
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
# --no-scripts pois o artisan ainda não está disponível nesta stage
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ============================================================
# Stage 3 — Imagem final de runtime (PHP 8.5)
# ============================================================
FROM php:8.5-cli-alpine AS app
WORKDIR /var/www/html

# Instalador de extensões PHP (facilita libs de sistema no Alpine)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    bcmath \
    intl \
    zip \
    pcntl \
    opcache

# Cliente do Postgres para o healthcheck/wait do entrypoint
RUN apk add --no-cache postgresql-client bash

# Código da aplicação
COPY . .

# Dependências e assets vindos das stages anteriores
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Permissões das pastas graváveis do Laravel
RUN chmod -R ug+rwX storage bootstrap/cache

# Entrypoint que prepara o ambiente e sobe o servidor
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 8000

ENTRYPOINT ["entrypoint"]
CMD ["serve"]

# ============================================================
# Stage 4 — Imagem de desenvolvimento (dev deps + Composer)
# ============================================================
# Usada apenas pelo compose.dev.yaml. Inclui o Composer e as
# dependências de dev (Faker, PHPUnit, Pint, etc.) e habilita
# o Xdebug via toggle do install-php-extensions.
FROM app AS dev

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Reinstala incluindo dependências de desenvolvimento
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

CMD ["serve"]
