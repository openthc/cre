#
# OpenTHC CRE Docker Image
#

FROM openthc/web:latest

#
# Install
#RUN git clone https://github.com/openthc/cre.git /opt/openthc/cre \
COPY ./ /opt/openthc/cre
RUN cd /opt/openthc/cre \
	&& ./make.sh

#
# Copy
COPY ./docker/rootfs/apache2.conf  /etc/apache2/sites-enabled/cre.conf
COPY ./docker/rootfs/openthc-init.sh  /openthc-cre


#
# Volumes
VOLUME [ "/opt/openthc/gfs" ]


#
# Environment
ENV OPENTHC_SERVER_NAME="cre.docker.openthc.me"


ENTRYPOINT [ "/openthc-cre" ]
