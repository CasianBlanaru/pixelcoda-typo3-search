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
export PIXELCODA_API_URL="${PIXELCODA_API_URL:-http://127.0.0.1:8787}"
export PIXELCODA_API_KEY="${PIXELCODA_API_KEY:-${API_WRITE_KEY:-pc_write_dev_key}}"
export PIXELCODA_READ_API_KEY="${PIXELCODA_READ_API_KEY:-${API_READ_KEY:-pc_read_dev_key}}"
export PIXELCODA_DEMO_EDITOR_USERNAME="${PIXELCODA_DEMO_EDITOR_USERNAME:-pixelcoda-editor}"
export PIXELCODA_DEMO_EDITOR_PASSWORD="${PIXELCODA_DEMO_EDITOR_PASSWORD:-PixelcodaDemo2026!}"
export PIXELCODA_DEMO_EDITOR_EMAIL="${PIXELCODA_DEMO_EDITOR_EMAIL:-demo@pixelcoda.de}"
export API_WRITE_KEY="${API_WRITE_KEY:-${PIXELCODA_API_KEY}}"
export API_READ_KEY="${API_READ_KEY:-${PIXELCODA_READ_API_KEY}}"
export SEARCH_DATA_DIR="${SEARCH_DATA_DIR:-/data/search-api}"
export SEARCH_API_PORT="${SEARCH_API_PORT:-8787}"
export SEARCH_API_HOST="${SEARCH_API_HOST:-127.0.0.1}"
export NODE_ENV=production

envsubst '${PORT} ${SEARCH_API_PORT}' \
    < /etc/apache2/sites-available/000-default.conf.template \
    > /etc/apache2/sites-available/000-default.conf
printf 'ServerName %s\n' "${RAILWAY_PUBLIC_DOMAIN:-localhost}" > /etc/apache2/conf-available/pixelcoda-server-name.conf
a2enconf pixelcoda-server-name >/dev/null

# Railway can retain Apache module state from an older service image. Enforce
# the mod_php-compatible MPM before starting Apache.
a2dismod -f mpm_event mpm_worker >/dev/null 2>&1 || true
a2enmod mpm_prefork >/dev/null 2>&1

mkdir -p \
    /data/config \
    /data/config/system \
    /data/fileadmin \
    /var/www/html/packages/ext \
    /var/www/html/packages/sysext/placeholder \
    /var/www/html/public/typo3temp \
    /var/www/html/public/typo3temp/assets \
    /var/www/html/public/typo3temp/assets/css \
    /var/www/html/public/typo3temp/assets/js \
    /var/www/html/var/cache/code/core/tmp \
    /var/www/html/var/cache/data/database_schema/tmp \
    /var/www/html/var
rm -rf /var/www/html/config /var/www/html/public/fileadmin
ln -s /data/config /var/www/html/config
ln -s /data/fileadmin /var/www/html/public/fileadmin
chown -R www-data:www-data /data /var/www/html/public/typo3temp /var/www/html/var

start_search_api() {
    if pgrep -f '/opt/pixelcoda-search-api/simple-api.js' >/dev/null 2>&1; then
        return 0
    fi

    echo "Starting pixelcoda Search API on ${SEARCH_API_HOST}:${SEARCH_API_PORT}..."
    node /opt/pixelcoda-search-api/simple-api.js >/tmp/pixelcoda-search-api.log 2>&1 &
    search_api_pid=$!

    for _attempt in $(seq 1 30); do
        if ! kill -0 "${search_api_pid}" >/dev/null 2>&1; then
            echo "pixelcoda Search API stopped during startup." >&2
            tail -n 80 /tmp/pixelcoda-search-api.log >&2 || true
            return 1
        fi
        if curl -fsS "http://127.0.0.1:${SEARCH_API_PORT}/health" >/dev/null 2>&1; then
            echo "pixelcoda Search API is ready."
            return 0
        fi
        sleep 1
    done

    echo "pixelcoda Search API did not become ready before Apache startup." >&2
    tail -n 80 /tmp/pixelcoda-search-api.log >&2 || true
    return 1
}

start_search_api || echo "Warning: Search API startup failed, continuing anyway..." >&2

db_configured=true
required_database_variables=(
    TYPO3_DB_HOST
    TYPO3_DB_DBNAME
    TYPO3_DB_USERNAME
    TYPO3_DB_PASSWORD
)
for variable in "${required_database_variables[@]}"; do
    if [[ -z "${!variable:-}" ]]; then
        echo "Warning: Missing MySQL variable: ${variable}" >&2
        db_configured=false
    fi
done

if [ "$db_configured" = true ]; then
    echo "Waiting for Railway MySQL (max 15 seconds)..."
    for _i in $(seq 1 5); do
        if mysqladmin ping \
            --host="${TYPO3_DB_HOST}" \
            --port="${TYPO3_DB_PORT}" \
            --user="${TYPO3_DB_USERNAME}" \
            --password="${TYPO3_DB_PASSWORD}" \
            --silent; then
            echo "MySQL is ready!"
            break
        fi
        sleep 3
    done
else
    echo "Database is not fully configured. Skipping connection wait." >&2
fi

if [[ -f /data/config/system/settings.php && ! -f /data/config/.setup-complete ]]; then
    touch /data/config/.setup-complete
fi

if [[ "$db_configured" = true && ! -f /data/config/system/settings.php ]]; then
    required_setup_variables=(
        TYPO3_SETUP_ADMIN_USERNAME
        TYPO3_SETUP_ADMIN_PASSWORD
    )
    setup_configured=true
    for variable in "${required_setup_variables[@]}"; do
        if [[ -z "${!variable:-}" ]]; then
            echo "Warning: Missing setup variable: ${variable}" >&2
            setup_configured=false
        fi
    done

    if [ "$setup_configured" = true ]; then
        rm -f /data/config/system/additional.php
        if vendor/bin/typo3 setup \
            --driver="${TYPO3_DB_DRIVER}" \
            --host="${TYPO3_DB_HOST}" \
            --port="${TYPO3_DB_PORT}" \
            --dbname="${TYPO3_DB_DBNAME}" \
            --username="${TYPO3_DB_USERNAME}" \
            --password="${TYPO3_DB_PASSWORD}" \
            --admin-username="${TYPO3_SETUP_ADMIN_USERNAME}" \
            --admin-user-password="${TYPO3_SETUP_ADMIN_PASSWORD}" \
            --admin-email="${TYPO3_SETUP_ADMIN_EMAIL:-}" \
            --project-name="${TYPO3_PROJECT_NAME}" \
            --create-site="${TYPO3_SETUP_CREATE_SITE}" \
            --server-type="${TYPO3_SERVER_TYPE}" \
            --no-interaction \
            --force \
            -vvv; then
            touch /data/config/.setup-complete
        else
            echo "TYPO3 first setup deferred; Apache will start for direct diagnostics." >&2
            php /usr/local/bin/pixelcoda-diagnose-bootstrap || true
            find /var/www/html/var/log -maxdepth 1 -type f -print -exec tail -n 120 {} \; 2>/dev/null || true
        fi
    fi
fi

if [ "$db_configured" = true ]; then
    cp /usr/local/share/pixelcoda-typo3-additional.php /data/config/system/additional.php

    vendor/bin/typo3 extension:setup --no-interaction -vvv \
        || {
            echo "TYPO3 extension setup deferred until the application container is available." >&2
            php /usr/local/bin/pixelcoda-diagnose-bootstrap || true
            find /var/www/html/var/log -maxdepth 1 -type f -print -exec tail -n 120 {} \; 2>/dev/null || true
        }

    vendor/bin/typo3 setup:begroups:default --groups=Both --no-interaction || true
    if ! mysql \
        --host="${TYPO3_DB_HOST}" \
        --port="${TYPO3_DB_PORT}" \
        --user="${TYPO3_DB_USERNAME}" \
        --password="${TYPO3_DB_PASSWORD}" \
        --batch \
        --skip-column-names \
        "${TYPO3_DB_DBNAME}" \
        -e "SELECT uid FROM be_users WHERE username='${PIXELCODA_DEMO_EDITOR_USERNAME}' AND deleted=0 LIMIT 1" \
        | grep -q '[0-9]'; then
        editor_group_ids="$(
            mysql \
                --host="${TYPO3_DB_HOST}" \
                --port="${TYPO3_DB_PORT}" \
                --user="${TYPO3_DB_USERNAME}" \
                --password="${TYPO3_DB_PASSWORD}" \
                --batch \
                --skip-column-names \
                "${TYPO3_DB_DBNAME}" \
                -e "SELECT GROUP_CONCAT(uid ORDER BY uid SEPARATOR ',') FROM be_groups WHERE title IN ('Editor','Advanced Editor') AND deleted=0"
        )"
        vendor/bin/typo3 backend:user:create \
            --username="${PIXELCODA_DEMO_EDITOR_USERNAME}" \
            --password="${PIXELCODA_DEMO_EDITOR_PASSWORD}" \
            --email="${PIXELCODA_DEMO_EDITOR_EMAIL}" \
            --groups="${editor_group_ids}" \
            --language=de \
            --no-interaction || true
    else
        demo_password_hash="$(php -r 'echo password_hash($argv[1], PASSWORD_ARGON2ID);' "${PIXELCODA_DEMO_EDITOR_PASSWORD}")"
        mysql \
            --host="${TYPO3_DB_HOST}" \
            --port="${TYPO3_DB_PORT}" \
            --user="${TYPO3_DB_USERNAME}" \
            --password="${TYPO3_DB_PASSWORD}" \
            "${TYPO3_DB_DBNAME}" \
            -e "UPDATE be_users SET password='${demo_password_hash}', admin=0, disable=0 WHERE username='${PIXELCODA_DEMO_EDITOR_USERNAME}' AND deleted=0" || true
    fi

    php /usr/local/bin/pixelcoda-configure-site || true
    php /usr/local/bin/pixelcoda-set-permissions || true
fi

vendor/bin/typo3 cache:flush || true
mkdir -p \
    /var/www/html/public/typo3temp/assets/css \
    /var/www/html/public/typo3temp/assets/js \
    /var/www/html/var/cache/code/core/tmp \
    /var/www/html/var/cache/data/database_schema/tmp
chown -R www-data:www-data /var/www/html/public/typo3temp /var/www/html/var
vendor/bin/typo3 cache:warmup || true

# Save environment variables for Apache/PHP to inherit
env | grep -E '^(TYPO3_|MYSQL|PIXELCODA_|API_)' | sed 's/^/export /' >> /etc/apache2/envvars || true

exec "$@"
