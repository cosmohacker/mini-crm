FROM nginx:1.22.1

COPY ./default.conf /etc/nginx/conf.d/default.conf

RUN apt-get update && apt-get install -y \
    nano