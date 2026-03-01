FROM php:8.5-apache

# SQLite開発ライブラリをインストールして拡張を有効化
RUN apt-get update && apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# ドキュメントルートを /var/www/html にコピー
COPY src/ /var/www/html/
COPY uploads/ /var/www/html/uploads/

# 権限調整
RUN chown -R www-data:www-data /var/www/html/uploads