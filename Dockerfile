FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libsqlite3-dev sqlite3 nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

RUN mkdir -p database
RUN touch database/database.sqlite

EXPOSE 8000

CMD sh -c "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"