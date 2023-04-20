FROM php:8.1-fpm-alpine

RUN apk --no-cache add git go libc-dev gcc g++ make jq \
    && go install github.com/projectdiscovery/nuclei/v2/cmd/nuclei@latest \
    && go install github.com/projectdiscovery/dnsx/cmd/dnsx@latest \
    && go install github.com/projectdiscovery/httpx/cmd/httpx@latest \
    && go install github.com/projectdiscovery/subfinder/v2/cmd/subfinder@latest

RUN docker-php-ext-install pdo pdo_mysql

COPY crontab /etc/crontabs/root
RUN mkdir /var/log/cron

ENV PATH="${PATH}:/root/go/bin"

CMD ["crond", "-f"]
