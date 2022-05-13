FROM golang:1.15 AS builder
RUN go get -d -v github.com/mailhog/mhsendmail \
    && cd /go/src/github.com/mailhog/mhsendmail/ \
    && GOOS=linux GOARCH=arm64 go build -o mhsendmail .

COPY ./php.ini /usr/local/etc/php/

FROM php:8.1-fpm
WORKDIR /var/www/html
RUN docker-php-ext-install pdo_mysql

# RUN curl -sSL https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 -o mhsendmail \
# RUN chmod +x mhsendmail \
#     && mv mhsendmail /usr/local/bin/mhsendmail
# COPY --from=builder /go/src/github.com/mailhog/mhsendmail/mhsendmail /usr/local/bin/

COPY --from=builder /go/src/github.com/mailhog/mhsendmail/mhsendmail /usr/local/bin/
RUN chmod +x /usr/local/bin/mhsendmail \
    && echo 'sendmail_path = "/usr/local/bin/mhsendmail --smtp-addr=mail:1025"' > /usr/local/etc/php/conf.d/sendmail.ini


