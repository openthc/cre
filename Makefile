#
#
#

SHELL = /bin/bash
.PHONY: help

export APP_ROOT := $(realpath $(@D) )

#
# Help, the default target
help:
	@echo
	@echo "You must supply a make command, try 'make system-install'"
	@echo
	@grep -ozP "#\n#.*\n[\w\-]+:" $(MAKEFILE_LIST) \
		| awk '/[a-zA-Z0-9_-]+:/ { printf "  \033[0;49;32m%-20s\033[0m%s\n", $$1, gensub(/^# /, "", 1, x) }; { x=$$0 }' \
		| sort
	@echo


#
# PHP Composer
composer:
	composer update --no-dev -a


#
# Documentation (asciidoc)
doc:
	mkdir -p ./webroot/doc

	asciidoctor \
		--verbose \
		--backend=html5 \
		--require=asciidoctor-diagram \
		--section-numbers \
		--out-file=./webroot/doc/index.html \
		./doc/index.ad


#
# NPM Update
npm:
	npm install


#
# Install Live Environment
install: composer npm

	cp node_modules/jquery/dist/jquery.min.js webroot/js/jquery.js

	cp node_modules/jquery-ui/dist/themes/base/jquery-ui.min.css webroot/css/jquery-ui.css
	cp node_modules/jquery-ui/dist/jquery-ui.js webroot/js/jquery-ui.js

	cp node_modules/bootstrap/dist/css/bootstrap.min.css webroot/css/bootstrap.css
	cp node_modules/bootstrap/dist/js/bootstrap.bundle.min.js webroot/js/bootstrap.js

#
# Run Tests
test: npm

	rm -fr ./webroot/test-output/
	composer update -a
	./test.sh
