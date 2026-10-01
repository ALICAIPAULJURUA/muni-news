#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

# Persistent disk may come up owned by root; hand it to the Apache user
chown -R www-data:www-data storage bootstrap/cache

# Generate an APP_KEY on first boot when the operator did not provide one
if [ -z "${APP_KEY:-}" ]; then
    php artisan key:generate --force
    echo "==> INFO: APP_KEY was auto-generated. Copy it into your Render env vars so sessions survive redeploys."
fi

# Ensure the SQLite database file lives on the persistent disk
: "${DB_DATABASE:=/var/www/html/storage/database.sqlite}"
mkdir -p "$(dirname "$DB_DATABASE")"
touch "$DB_DATABASE"
chown www-data:www-data "$DB_DATABASE"

# Migrate + seed. DatabaseSeeder is fully idempotent (firstOrCreate), so this is
# safe to run on every boot and bootstraps a fresh database with roles/admin.
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction

# Make uploaded files reachable via /storage
php artisan storage:link --force || true

exec apache2-foreground