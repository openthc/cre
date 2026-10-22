#!/bin/bash

# Make docker
docker build . -f docker/Dockerfile --no-cache --tag openthc/cre

