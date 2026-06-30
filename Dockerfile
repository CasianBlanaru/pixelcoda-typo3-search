FROM php:8.3-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        locales \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen en_US.UTF-8 \
    && apt-get install -y --no-install-recommends \
        default-mysql-client \
        gettext-base \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        libzip-dev \
        curl \
        nodejs \
        npm \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" dom gd intl mbstring mysqli opcache pdo_mysql xml zip \
    && (a2dismod -f mpm_event mpm_worker || true) \
    && a2enmod mpm_prefork \
    && a2enmod deflate expires headers proxy proxy_http rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY packages ./packages
COPY composer.json composer.lock ./

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install with retry logic for GitHub API rate limits
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    || (echo "Retry with source fallback..." && composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --prefer-source \
        --optimize-autoloader) \
    && test -f vendor/typo3/PackageArtifact.php \
    && test -f vendor/typo3/alias-loader-include.php \
    && cp vendor/typo3/cms-install/Resources/Private/FolderStructureTemplateFiles/root-htaccess public/.htaccess

# TYPO3 14 scans packages/sysext during a fresh installation. Composer already
# registers framework packages, but Finder still requires a scanable directory.
RUN mkdir -p packages/ext packages/sysext/placeholder

COPY config config
COPY config /usr/local/share/typo3-config

COPY deployment/typo3/apache-vhost.conf.template /etc/apache2/sites-available/000-default.conf.template
COPY deployment/typo3/entrypoint.sh /usr/local/bin/pixelcoda-typo3-entrypoint
COPY deployment/typo3/configure-site.php /usr/local/bin/pixelcoda-configure-site
COPY deployment/typo3/set-permissions.php /usr/local/bin/pixelcoda-set-permissions
COPY deployment/typo3/diagnose-bootstrap.php /usr/local/bin/pixelcoda-diagnose-bootstrap
COPY deployment/typo3/additional.php /usr/local/share/pixelcoda-typo3-additional.php
COPY deployment/typo3/force-headless-config.php /usr/local/bin/pixelcoda-force-headless-config

RUN mkdir -p /var/www/html/deployment/typo3
COPY deployment/typo3/*.sql /var/www/html/deployment/typo3/
COPY public/index.php public/index.php
COPY deployment/typo3/backend-index.php public/typo3/index.php
COPY deployment/typo3/install-index.php public/typo3/install.php
COPY deployment/typo3/healthz.php public/healthz.php
COPY deployment/typo3/favicon.svg public/favicon.svg
COPY deployment/typo3/favicon.svg public/favicon.ico
COPY deployment/typo3/php-production.ini /usr/local/etc/php/conf.d/zz-pixelcoda-production.ini
COPY simple-api.js index.html package.json /opt/pixelcoda-search-api/
RUN cd /opt/pixelcoda-search-api && npm install --omit=dev

RUN chmod +x /usr/local/bin/pixelcoda-typo3-entrypoint \
    && mkdir -p \
        /data \
        /var/www/html/packages/ext \
        /var/www/html/packages/sysext/placeholder \
        /var/www/html/public/fileadmin \
        /var/www/html/public/typo3temp/assets \
        /var/www/html/var \
        /data/search-api \
    && chown -R www-data:www-data /data /var/www/html

ENV PORT=8080 \
    TYPO3_CONTEXT=Production \
    TYPO3_PATH_APP=/var/www/html \
    TYPO3_PATH_ROOT=/var/www/html/public \
    LANG=en_US.UTF-8 \
    LANGUAGE=en_US:en \
    LC_ALL=en_US.UTF-8

EXPOSE 8080

ENTRYPOINT ["pixelcoda-typo3-entrypoint"]
CMD ["apache2-foreground"]
