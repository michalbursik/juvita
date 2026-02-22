SAIL = ./vendor/bin/sail
PHP = $(SAIL) php
ARTISAN = $(SAIL) artisan
COMPOSER = $(SAIL) composer
NPM = $(SAIL) npm

.PHONY: help install up down restart test fresh dev build shell tinker

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Install dependencies and bootstrap the project
	cp .env.example .env || true
	docker run --rm \
        -u "$$(id -u):$$(id -g)" \
        -v "$$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php84-composer:latest \
        composer install --ignore-platform-reqs
	$(MAKE) up
	$(ARTISAN) key:generate
	$(NPM) install
	$(MAKE) fresh

up: ## Start the application containers
	$(SAIL) up -d

down: ## Stop the application containers
	$(SAIL) down

restart: ## Restart the application containers
	$(SAIL) restart

test: ## Run the test suite
	$(ARTISAN) test --compact

fresh: ## Refresh the database and run seeders
	$(ARTISAN) migrate:fresh --seed

dev: ## Start Sail
	$(MAKE) up

build: ## Build assets for production
	$(NPM) run prod

lint: ## Run Laravel Pint to fix code style
	$(SAIL) pint

shell: ## Open a shell in the application container
	$(SAIL) shell

tinker: ## Open Laravel Tinker
	$(ARTISAN) tinker

local-check: ## Run code quality tools (PHPStan, Pint, Tests) and generate IDE helper types
	$(ARTISAN) ide-helper:generate
	$(ARTISAN) ide-helper:models --nowrite
	$(ARTISAN) ide-helper:meta
	$(SAIL) pint
	#$(PHP) vendor/bin/phpstan analyse --memory-limit=2G # many errors, so far skip
	$(MAKE) test
