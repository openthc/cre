#!/bin/bash
#
# OpenTHC CRE Docker Init
#

set -o errexit
set -o errtrace
set -o nounset
set -o pipefail


#
# PHP Debugger
OPENTHC_DEBUG=${OPENTHC_DEBUG:-"false"}
if [ "$OPENTHC_DEBUG" == "true" ]
then
	echo "DEBUG ENABLED"
	phpenmod xdebug
fi


#
# Uses Environment to Create App
/opt/openthc/cre/docker/init.php


#
# Unsets All OpenTHC Environment Variables
# Except for OPENTHC_SERVICE
for var in $(env | cut -d= -f1 | grep OPENTHC | grep -v OPENTHC_SER)
do
	# echo "unset $var"
	unset "$var"
done


# Start PHP
/etc/init.d/php8.4-fpm start

# Start Caddy
exec caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
