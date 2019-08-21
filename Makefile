VERSION = $(shell git describe --tags --always --dirty)
BRANCH = $(shell git rev-parse --abbrev-ref HEAD)

.PHONY: help shell test clean

all: help

help:
	@echo
	@echo "VERSION: $(VERSION)"
	@echo "BRANCH: $(BRANCH)"
	@echo
	@echo "usage: make <command>"
	@echo
	@echo "commands:"
	@echo "    shell            - create docker container and enter the container"
	@echo "    test             - run tests"
	@echo "    test-coverage    - run tests with coverage"
	@echo "    clean            - remove generated files and directories"
	@echo "    update           - set the environment up"
	@echo "    destroy          - destroy schema"
	@echo

shell:
	@docker-compose up -d testing-php
	@docker-compose exec testing-php bash

test:
	@docker-compose up -d testing-php
	@docker-compose exec testing-php ./vendor/bin/phpunit

test-coverage:
	@docker-compose up -d testing-php
	@docker-compose exec testing-php ./vendor/bin/phpunit --coverage-html coverage

clean:
	@docker-compose stop
	@docker-compose rm -v --force

update:
	@docker-compose up -d testing-php

destroy:
	@docker-compose up -d testing-php
