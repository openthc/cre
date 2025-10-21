# syntax=docker/dockerfile:1.19
#
# OpenTHC CRE Docker Image
#

FROM openthc/web:latest

#
# Environment
ENV OPENTHC_SERVICE="cre"
ENV OPENTHC_SERVER_NAME="cre.docker.openthc.org"

#
# Install
COPY --exclude=docker/ ./ /opt/openthc/cre
RUN /opt/openthc/cre/make.sh

#
# Configure
COPY ./docker/apache2.conf  /etc/apache2/sites-enabled/000-default.conf
COPY ./docker/Caddyfile     /etc/caddy/Caddyfile
COPY ./docker/xdebug.ini    /etc/php/7.4/mods-available/xdebug.ini
COPY ./docker/init.sh       /init.sh
COPY ./docker/init.php      /opt/openthc/cre/docker/init.php

#
# Volumes
VOLUME [ "/opt/openthc/gfs" ]

#
# Ports
EXPOSE 80

#
# Go!
ENTRYPOINT [ "/init.sh" ]
