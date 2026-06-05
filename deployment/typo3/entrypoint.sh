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
export TYPO3_PROJECT_NAME="${TYPO3_PROJECT_NAME:-Pixelcoda TYPO3 Suite}"
export TYPO3_SERVER_TYPE=apache
export TYPO3_PATH_APP="${TYPO3_PATH_APP:-/var/www/html}"
export TYPO3_PATH_ROOT="${TYPO3_PATH_ROOT:-/var/www/html/public}"

envsubst '${PORT}' \
    < /etc/apache2/sites-available/000-default.conf.template \
    > /etc/apache2/sites-available/000-default.conf

# Railway can retain Apache module state from an older service image. Enforce
# the mod_php-compatible MPM before starting Apache.
a2dismod -f mpm_event mpm_worker >/dev/null 2>&1 || true
a2enmod mpm_prefork >/dev/null 2>&1

mkdir -p /data/config /data/fileadmin /var/www/html/var
rm -rf /var/www/html/config /var/www/html/public/fileadmin
ln -s /data/config /var/www/html/config
ln -s /data/fileadmin /var/www/html/public/fileadmin
chown -R www-data:www-data /data /var/www/html/var

required_database_variables=(
    TYPO3_DB_HOST
    TYPO3_DB_DBNAME
    TYPO3_DB_USERNAME
    TYPO3_DB_PASSWORD
)
for variable in "${required_database_variables[@]}"; do
    if [[ -z "${!variable:-}" ]]; then
        echo "Missing required Railway MySQL variable: ${variable}" >&2
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

if [[ ! -f /data/config/system/settings.php ]]; then
    required_setup_variables=(
        TYPO3_SETUP_ADMIN_USERNAME
        TYPO3_SETUP_ADMIN_PASSWORD
    )
    for variable in "${required_setup_variables[@]}"; do
        if [[ -z "${!variable:-}" ]]; then
            echo "Missing required first-deploy variable: ${variable}" >&2
            exit 1
        fi
    done

    vendor/bin/typo3 setup --no-interaction --force
fi

vendor/bin/typo3 extension:setup --no-interaction
php /usr/local/bin/pixelcoda-configure-site
vendor/bin/typo3 cache:flush --group=system || true
vendor/bin/typo3 cache:warmup || true

exec "$@"
