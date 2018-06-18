MAKEFLAGS += --silent


.PHONY: help install run stop test

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

composer-install: ## Run composer install within the host
	docker-compose run --no-deps --rm \
		service_php bash -ci 'bin/composer install'

database-install: ## Create and setup the database
	docker-compose up -d service_postgres

install: ## Install docker environnement
	docker-compose build
	$(MAKE) composer-install
	$(MAKE) database-install

run: ## Start the server
	docker-compose up -d

stop: ## Stop the server
	docker-compose down

test: ## Test the code
	docker build -t service_php docker/php
	$(MAKE) composer-install
	docker run -it --rm -v "${PWD}/app:/app" service_php bin/phpunit

.DEFAULT_GOAL := help
