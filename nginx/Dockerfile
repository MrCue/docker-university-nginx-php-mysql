FROM debian:jessie

MAINTAINER Steve Greenwood <steve@lythamcomputers.com>

RUN apt-get update && apt-get install -y \
    nginx

ADD nginx.conf /etc/nginx/
ADD project.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/project.conf /etc/nginx/sites-enabled/project.conf \
&& rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
