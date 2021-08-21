#!/bin/bash
#
#
#

BIN_SELF=$(readlink -f "$0")
APP_ROOT=$(dirname "$BIN_SELF")

cd "$APP_ROOT"

action="$1"
shift

case "$action" in
# Initialize the System
init)
	echo "Add Contact"
	;;
# Add a Contact
add-contact)
	echo "Add Contact"
	;;
# Build Documentation
doc)

	mkdir -p ./webroot/doc

	asciidoctor \
		--verbose \
		--backend=html5 \
		--require=asciidoctor-diagram \
		--section-numbers \
		--out-file=./webroot/doc/index.html \
		./doc/index.ad

	;;
help|*)
	awk '/^# [A-Z].+/ { h=$0 }; /^[a-z]+.+\)/ { printf " \033[0;49;31m%-15s\033[0m%s\n", gensub(/\)$/, "", 1, $$1), h }' "$BIN_SELF" | sort
esac
