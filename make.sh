#!/bin/bash
#
# Install Helper
#
# SPDX-License-Identifier: MIT
#

set -o errexit
set -o errtrace
set -o nounset
set -o pipefail


BIN_SELF=$(readlink -f "$0")
APP_ROOT=$(dirname "$BIN_SELF")

cd "$APP_ROOT"

function _clean()
{
	# clean-pack
	# Cleanup the Assembled JS/CSS
	rm -fv ./webroot/less/app.less webroot/css/app.css webroot/css/app.css.gz
	rm -fv ./webroot/js/app.js webroot/js/app.js.gz

	rm -fr ./node_modules

}

function _docs()
{
	mkdir -p ./webroot/doc

	asciidoctor \
		--verbose \
		--backend=html5 \
		--require=asciidoctor-diagram \
		--section-numbers \
		--out-file=./webroot/doc/index.html \
		./doc/index.ad

	# Gherkin
	mkdir -p webroot/doc/
	php ./test/gherkin2html.php > webroot/doc/feature-list.html

	# Doxygen
	doxygen

	grep 'is not documented' doxygen.out > "webroot/doc/doxygen/html/no-doc.txt" || true
	grep -v 'is not documented' doxygen.out > doxygen.tmp && mv doxygen.tmp doxygen.out

	grep 'was not declared or defined' doxygen.out > "webroot/doc/doxygen/html/no-def.txt" || true
	grep -v 'was not declared or defined' doxygen.out > doxygen.tmp && mv doxygen.tmp doxygen.out
	mv doxygen.out "webroot/doc/doxygen/html/doxygen.txt"

	# rsync --archive --delete "doxygen/html/" "webroot/doc/doxygen/"

}

# Do Stuff
action="${1:-}"
case "$action" in
#
# Clean up the junk
clean)
	_clean
	;;

#
# Make some Documentation
docs)
	_docs
	;;

#
# Install or Update the OpenTHC Application
install)

	composer update --no-ansi --no-dev --no-progress --quiet --classmap-authoritative

	npm install --quiet

	. vendor/openthc/common/lib/lib.sh

	copy_bootstrap
	copy_fontawesome
	copy_jquery

	# _docs()

	;;

# Help, the default target
"--help"|"help"|*)

	echo
	echo "You must supply a make command"
	echo
	awk '/^# [A-Z].+/ { h=$0 }; /^[\-0-9a-z]+\)/ { printf " \033[0;49;31m%-20s\033[0m%s\n", gensub(/\)$/, "", 1, $$1), h }' "$BIN_SELF" \
		| sort
	echo

esac

# https://github.com/kucherenko/jscpd
#
# #
# # Make all the things for live
# live: css-full js-full
# 	#git clone https://github.com/yasirsiddiqui/php-google-cloud-print.git ./lib/php-google-cloud-print
