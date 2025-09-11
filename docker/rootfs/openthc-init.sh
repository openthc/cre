#!/bin/bash
#
#

set -o errexit
set -o errtrace
set -o nounset
set -o pipefail


# Test if Run Before
if [ ! -f /first-run.txt ]
then
	# connect to SQL and get details?
	echo "RUN0"
	# php /first-run.php
	# rm /first-run.php
	touch /first-run.txt
else
	echo "RUN1+"
fi

#
# PHP Debugger
OPENTHC_DEBUG=${OPENTHC_DEBUG:-"false"}
if [ "$OPENTHC_DEBUG" == "true" ]
then
	echo "DEBUG ENABLED"
	phpenmod xdebug
fi


# Start Regular Way
exec /usr/sbin/apache2 -DFOREGROUND
