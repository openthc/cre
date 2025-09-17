#
# OpenTHC CRE Docker Image
#

FROM openthc/web:latest

#
# Environment
ENV OPENTHC_SERVER_NAME="cre.docker.openthc.me"


#
# Install
COPY ./ /opt/openthc/cre
RUN cd /opt/openthc/cre \
	&& ./make.sh


#
# Configure
COPY ./docker/rootfs/apache2.conf     /etc/apache2/sites-enabled/000-default.conf
COPY ./docker/rootfs/openthc-init.sh  /init.sh
COPY ./docker/rootfs/xdebug.ini       /etc/php/7.4/mods-available/xdebug.ini


#
# Volumes
VOLUME [ "/opt/openthc/gfs" ]


#
# Ports
EXPOSE 80


ENTRYPOINT [ "/init.sh" ]
