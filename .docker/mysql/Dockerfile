# This docker file constructs a MySsQL database instance
FROM mysql:latest

ADD . /gdms-rcon/mysql
WORKDIR /gdms-rcon/mysql

ENTRYPOINT ["/entrypoint.sh"]