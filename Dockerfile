FROM php:7.2.27-cli

RUN curl https://phar.phpunit.de/phpunit-8.5.2.phar -o /usr/local/bin/phpunit \
  && chmod +x /usr/local/bin/phpunit

ADD check_site /check_site
ADD cdn /cdn