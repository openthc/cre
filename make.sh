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

APP_ROOT=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

cd "$APP_ROOT"

composer install --no-ansi --no-progress --classmap-authoritative

npm install --no-audit --no-fund

php <<PHP
<?php
define('APP_ROOT', __DIR__);
require_once(APP_ROOT . '/vendor/autoload.php');
\OpenTHC\Make::install_bootstrap();
\OpenTHC\Make::install_fontawesome();
\OpenTHC\Make::install_jquery();
PHP


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

esac
