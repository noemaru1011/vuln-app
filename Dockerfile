FROM php:8.5-apache

# SQLiteと必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    msmtp \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# msmtpの設定ファイルを修正
RUN echo "defaults" > /etc/msmtprc && \
    echo "account default" >> /etc/msmtprc && \
    echo "host mailpit" >> /etc/msmtprc && \
    echo "port 1025" >> /etc/msmtprc && \
    echo "auto_from off" >> /etc/msmtprc && \
    echo "from system@example.com" >> /etc/msmtprc && \
    echo "domain localhost" >> /etc/msmtprc && \
    chmod 644 /etc/msmtprc

# PHPの設定も再確認
RUN echo "sendmail_path = \"/usr/bin/msmtp -t -i -C /etc/msmtprc\"" > /usr/local/etc/php/conf.d/mail.ini

COPY src/ /var/www/html/
COPY uploads/ /var/www/html/uploads/
RUN chown -R www-data:www-data /var/www/html/uploads