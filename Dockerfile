FROM php:8.3-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        default-mysql-client \
        gettext-base \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd intl mysqli opcache pdo_mysql zip \
    && (a2dismod -f mpm_event mpm_worker || true) \
    && a2enmod mpm_prefork \
    && a2enmod deflate expires headers rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY typo3-dev/packages ./packages
COPY typo3-dev/composer.json typo3-dev/composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

# TYPO3 14 scans packages/sysext during a fresh installation. Composer keeps
# framework packages in vendor, so expose them at the expected scan location.
RUN mkdir -p packages/ext packages/sysext \
    && for package in vendor/typo3/cms-*; do \
        if grep -q '"type": "typo3-cms-framework"' "${package}/composer.json"; then \
            ln -s "../../${package}" "packages/sysext/$(basename "${package}")"; \
        fi; \
    done

COPY deployment/typo3/apache-vhost.conf.template /etc/apache2/sites-available/000-default.conf.template
COPY deployment/typo3/entrypoint.sh /usr/local/bin/pixelcoda-typo3-entrypoint
COPY deployment/typo3/configure-site.php /usr/local/bin/pixelcoda-configure-site
COPY deployment/typo3/additional.php /usr/local/share/pixelcoda-typo3-additional.php
COPY deployment/typo3/healthz.php public/healthz.php
COPY deployment/typo3/php-production.ini /usr/local/etc/php/conf.d/zz-pixelcoda-production.ini

RUN chmod +x /usr/local/bin/pixelcoda-typo3-entrypoint \
    && mkdir -p \
        /data \
        /var/www/html/packages/ext \
        /var/www/html/packages/sysext \
        /var/www/html/public/fileadmin \
        /var/www/html/var \
    && chown -R www-data:www-data /data /var/www/html

ENV PORT=8080 \
    TYPO3_CONTEXT=Production \
    TYPO3_PATH_APP=/var/www/html \
    TYPO3_PATH_ROOT=/var/www/html/public

EXPOSE 8080

ENTRYPOINT ["pixelcoda-typo3-entrypoint"]
CMD ["apache2-foreground"]
