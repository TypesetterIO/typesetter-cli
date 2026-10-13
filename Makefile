PHP ?= php
COMPOSER ?= composer
WORKFLOW = .github/workflows/ci.yml
ACT_IMAGE = ubuntu-latest=shivammathur/node:latest

.DEFAULT_GOAL := help
.PHONY: help install phpcs phpcs-fix phpstan hadolint test coverage ci act

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## ' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-10s %s\n", $$1, $$2}'

install: ## composer install
	$(COMPOSER) install --no-progress --no-ansi --prefer-dist

phpcs: ## PHP CS Fixer check
	$(PHP) vendor/bin/php-cs-fixer check --show-progress=none

phpcs-fix: ## PHP CS Fixer fix
	$(PHP) vendor/bin/php-cs-fixer fix --show-progress=none

phpstan: ## PHPStan
	$(PHP) vendor/bin/phpstan analyse

hadolint: ## hadolint on the Dockerfile
	hadolint Dockerfile

test: ## PHPUnit
	$(PHP) vendor/bin/phpunit

coverage: ## HTML coverage report in coverage/ (needs Xdebug)
	XDEBUG_MODE=coverage $(PHP) vendor/bin/phpunit --coverage-html coverage --path-coverage

ci: hadolint phpcs phpstan test ## hadolint, phpcs, phpstan, test in order

act: ## Run the CI matrix in Docker (PHP_VERSION=8.4 for one cell)
	act workflow_dispatch -W $(WORKFLOW) -P $(ACT_IMAGE) $(if $(PHP_VERSION),--matrix php:$(PHP_VERSION))
