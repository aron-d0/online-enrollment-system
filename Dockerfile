FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libsqlite3-dev sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p database
RUN touch database/database.sqlite

EXPOSE 8000

CMD sh -c "php -S 0.0.0.0:${PORT:-8000} -t public"