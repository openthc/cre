#
# OpenTHC CRE Docker Image
#

FROM openthc/web:latest

#
# Environment
ENV OPENTHC_SERVER_NAME="cre.docker.openthc.me"


#
# Install
#RUN git clone https://github.com/openthc/cre.git /opt/openthc/cre \
COPY ./ /opt/openthc/cre
RUN cd /opt/openthc/cre \
	&& ./make.sh

#
# Configure
COPY ./docker/rootfs/apache2.conf     /etc/apache2/sites-enabled/cre.conf
COPY ./docker/rootfs/openthc-init.sh  /openthc-cre
COPY ./docker/rootfs/xdebug.ini       /etc/php/7.4/mods-available/xdebug.ini


#
# Volumes
VOLUME [ "/opt/openthc/gfs" ]


#
# Ports
EXPOSE 80


#
ENTRYPOINT [ "/openthc-cre" ]
