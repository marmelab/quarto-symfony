.PHONY: help install run stop test

.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

composer-install: ## Run composer install within the host
	docker-compose run --no-deps --rm \
		php bash -ci 'bin/composer install'

init-db: ## Create and setup the database
	docker-compose up -d postgres
	docker-compose run --no-deps --rm php \
		bash -ci './bin/console doctrine:database:create --if-not-exists && ./bin/console doctrine:schema:update --force'
	docker-compose down

install: composer-install ## Install docker environnement

run: ## Start the server
	docker-compose up -d

stop: ## Stop the server
	docker-compose down

test: ## Test the code
	docker-compose run --no-deps --rm php bin/phpunit

deploy: ## Deploy website on Web Server (Need an sshname parameter for distant connection)
	zip -r quarto.zip quarto composer.json composer-setup.php composer.lock -x /quarto/var/cache/dev/* /quarto/vendor/* /quarto/.env /quarto/.env.dist /quarto/config/packages/doctrine.yaml
	ssh $(sshname) mkdir -p quarto-symfony
	scp -v quarto.zip $(sshname):~/quarto-symfony/
	ssh $(sshname) 'unzip -uo ~/quarto-symfony/quarto.zip -d ~/quarto-symfony/'
	ssh $(sshname) 'rm -f ~/quarto-symfony/quarto.zip'
	rm -f quarto.zip
	ssh $(sshname) 'cd quarto-symfony/quarto && ./bin/composer install'
	ssh $(sshname) "bash -ci './quarto-symfony/quarto/bin/console doctrine:database:create --if-not-exists && ./quarto-symfony/quarto/bin/console doctrine:schema:update --force'"
	ssh $(sshname) sudo service nginx restart
	

