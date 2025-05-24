#!/usr/bin/env sh
set -e

echo "⏳ Aguardando MySQL em $DB_HOST:$DB_PORT …"
until mysqladmin ping \
    -h"$DB_HOST" \
    -P"$DB_PORT" \
    -u"$DB_USERNAME" \
    -p"$DB_PASSWORD" \
    --silent; do
  sleep 2
done
echo "✅ Banco de dados acessível."

cd /var/www/html

#Garante .env existente
if [ ! -f .env ]; then
  cp .env.example .env
  echo "📝 Copiado .env.example para .env"
fi

#Instala dependências Composer
composer install --no-interaction --prefer-dist --optimize-autoloader
echo "📦 Dependências instaladas via Composer"

#Gera APP_KEY apenas se não existir
APP_KEY=$(grep '^APP_KEY=' .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --ansi
  echo "🔑 APP_KEY gerada"
else
  echo "🔑 APP_KEY já definida, pulando generate"
fi

#Executa migrations + seed apenas uma vez
LOCK_FILE="storage/.migrated"
if [ ! -f "$LOCK_FILE" ]; then
  php artisan migrate --force --ansi
  php artisan db:seed --force --ansi
  touch "$LOCK_FILE"
  echo "🚧 Migrations e seed aplicados pela primeira vez"
else
  echo "🚧 Migrations já rodadas, pulando"
fi

echo "🚀 Iniciando Apache…"
if [ "$1" = "test" ]; then
  echo "🧪 Executando testes..."
  XDEBUG_MODE=coverage php artisan test
  exit $?
fi
exec apache2-foreground
