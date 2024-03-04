# Variables
default: help
SYMFONY = symfony console
APP = vicyma

run: ## Start project
	make composer-install && \
	make localenv && \
	make db && \
	make cacheclear && \
	yarn build && \
	symfony server:start -d && \
	symfony open:local && \

stop: ## Stop Server
	symfony server:stop

localenv: ## Create .env.local file
	if [ ! -f '.env.local' ]; then \
		touch .env.local && echo "DATABASE_URL='mysql://root:root@127.0.0.1:8889/$(APP)'" >> .env.local; \
	fi

cacheclear: ## Clear symfony cache
	$(SYMFONY) cache:clear \

db: ## Create or Reload Database
	$(SYMFONY) d:d:d -f --if-exists && \
	$(SYMFONY) d:d:c --if-not-exists && \
	$(SYMFONY) d:m:m --no-interaction && \
	$(SYMFONY) make:migration && \
	$(SYMFONY) d:m:m --no-interaction && \
	$(SYMFONY) d:f:l --no-interaction

composer-install: ## Install dependencies
	composer install --no-interaction

runtests: ## Run Tests / testName=TESTNAME to only run TESTNAME
	@if [ -z $(testName) ]; then \
		php bin/phpunit --colors=always; \
    else \
		php bin/phpunit --colors=always --filter=$(testName); \
	fi

help: ## Show this help menu
	@awk -F ':|##' '/^[^\t].+?:.*?##/ {printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF}' $(MAKEFILE_LIST)