.PHONY: help install run stop test

.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

composer-install: ## Run composer install within the host
	docker-compose run --no-deps --rm \
		php bash -ci 'bin/composer install'

install: composer-install ## Install docker environnement

run: ## Start the server
	docker-compose up -d

stop: ## Stop the server
	docker-compose down

test: ## Test the code
	docker-compose run --no-deps --rm \
		php bash -ci 'php bin/phpunit'
