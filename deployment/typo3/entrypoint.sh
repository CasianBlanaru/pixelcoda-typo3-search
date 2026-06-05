#!/usr/bin/env bash
set -euo pipefail

export PORT="${PORT:-8080}"
export TYPO3_DB_DRIVER="${TYPO3_DB_DRIVER:-mysqli}"
export TYPO3_DB_HOST="${TYPO3_DB_HOST:-${MYSQLHOST:-}}"
export TYPO3_DB_PORT="${TYPO3_DB_PORT:-${MYSQLPORT:-3306}}"
export TYPO3_DB_DBNAME="${TYPO3_DB_DBNAME:-${MYSQLDATABASE:-}}"
export TYPO3_DB_USERNAME="${TYPO3_DB_USERNAME:-${MYSQLUSER:-}}"
export TYPO3_DB_PASSWORD="${TYPO3_DB_PASSWORD:-${MYSQLPASSWORD:-}}"
export TYPO3_SETUP_CREATE_SITE="${TYPO3_SETUP_CREATE_SITE:-https://${RAILWAY_PUBLIC_DOMAIN:-localhost}/}"
export TYPO3_PROJECT_NAME="${TYPO3_PROJECT_NAME:-Pixelcoda TYPO3 Search}"
export TYPO3_SERVER_TYPE=apache

envsubst '${PORT}' \
    < /etc/apache2/sites-available/000-default.conf.template \
    > /etc/apache2/sites-available/000-default.conf

mkdir -p /data/config /data/fileadmin /data/var
rm -rf /var/www/html/config /var/www/html/public/fileadmin /var/www/html/var
ln -s /data/config /var/www/html/config
ln -s /data/fileadmin /var/www/html/public/fileadmin
ln -s /data/var /var/www/html/var
chown -R www-data:www-data /data

if [[ ! -f /data/config/system/settings.php ]]; then
    required_variables=(
        TYPO3_DB_HOST
        TYPO3_DB_DBNAME
        TYPO3_DB_USERNAME
        TYPO3_DB_PASSWORD
        TYPO3_SETUP_ADMIN_USERNAME
        TYPO3_SETUP_ADMIN_PASSWORD
    )
    for variable in "${required_variables[@]}"; do
        if [[ -z "${!variable:-}" ]]; then
            echo "Missing required first-deploy variable: ${variable}" >&2
            exit 1
        fi
    done

    echo "Waiting for Railway MySQL..."
    until mysqladmin ping \
        --host="${TYPO3_DB_HOST}" \
        --port="${TYPO3_DB_PORT}" \
        --user="${TYPO3_DB_USERNAME}" \
        --password="${TYPO3_DB_PASSWORD}" \
        --silent; do
        sleep 2
    done

    vendor/bin/typo3 setup --no-interaction --force
    vendor/bin/typo3 extension:setup
fi

vendor/bin/typo3 cache:warmup || true

exec "$@"
