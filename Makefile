# Variables
default: help
SYMFONY = symfony console
APP = vicyma
MYSQL_USERNAME = root
MYSQL_PASSWORD = root

run: ## Start project
	$(info [VICYMA] -- [ ------------- Starting project... ------------- ])

	@make composer-install && \
	clear && \
	make createlocalenv && \
	make filllocalenv && \
	clear && \
	make db && \
	make cacheclear && \
	clear && \
	make yarnbuild && \
	make serverstart && \
	clear && \
	make openbrowser && \
	@echo [VICYMA] -- [OK]

db: ## Create or Reload Database
	$(info [VICYMA] -- [ ------------- Dropping, creating, migrating and loading fixtures... ------------- ])
	@make dbdrop && \
	make dbcreate && \
	make dbmigrate && \
	make dbmigration && \
	make dbmigrate && \
	make dbfixtures

yarnbuild: ## Building public assets
	$(info [VICYMA] ------------- || Building public assets... )
	yarn build

openbrowser: ## Open project in $BROWSER
	$(info [VICYMA] ------------- || Opening project in your browser... )
	@symfony open:local

serverstart: ## Start server
	$(info [VICYMA] ------------- || Starting server... )
	symfony server:start -d

serverstop: ## Stop server
	$(info [VICYMA] ------------- || Stopping server... )
	@symfony server:stop

createlocalenv: ## Create .env.local file
	$(info [VICYMA] ------------- || Creating env file... )
	@if [ ! -f '.env.local' ]; then \
		touch .env.local; \
	fi

filllocalenv: ## Create .env.local file
	$(info [VICYMA] ------------- || Filling env , using 'root:root'... )
	echo "DATABASE_URL='mysql://$(MYSQL_USERNAME):$(MYSQL_PASSWORD)@127.0.0.1:8889/$(APP)'" | tee .env.local; \

cacheclear: ## Clear symfony cache
	$(info [VICYMA] ------------- || Clearing cache... )
	@$(SYMFONY) cache:clear \

dbdrop: ## Drop database
	$(info [VICYMA] ------------- || Dropping database... )
	$(SYMFONY) d:d:d -f --if-exists

dbcreate: ## Create database
	$(info [VICYMA] ------------- || Creating database... )
	$(SYMFONY) d:d:c --if-not-exists

dbmigrate: ## Migrating databse schema
	$(info [VICYMA] ------------- || Migrating database... )
	$(SYMFONY) d:m:m --no-interaction

dbmigration: ## Create database migration
	$(info [VICYMA] ------------- || Creating database migration... )
	$(SYMFONY) make:migration

dbfixtures: ## Load fixtures
	$(info [VICYMA] ------------- || Loading fixtures... )
	$(SYMFONY) d:f:l --no-interaction

composer-install: ## Install dependencies
	$(info [VICYMA] ------------- || Installing composer dependencies... )
	@composer install --no-interaction

runtests: ## Run Tests / testName=TESTNAME to only run TESTNAME
	$(info [VICYMA] ------------- || Running tests... )
	@if [ -z $(testName) ]; then \
		php bin/phpunit --colors=always; \
    else \
		php bin/phpunit --colors=always --filter=$(testName); \
	fi

help: ## Show this help menu
	@awk -F ':|##' '/^[^\t].+?:.*?##/ {printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF}' $(MAKEFILE_LIST)