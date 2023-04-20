FROM kalilinux/kali-rolling

RUN apt update -y && apt upgrade -y \
&& apt install -y php \
&& apt install -y php-cli \
&& apt install -y php-mbstring \
&& apt install -y php-xml \
&& apt install -y composer \
&& apt install -y curl \
&& curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
&& apt install -y symfony-cli

WORKDIR /app

COPY . .

RUN apt-get install -y php-mysql

RUN composer update

RUN composer install


EXPOSE 8000

CMD [ "symfony", "server:start"]
