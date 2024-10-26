# QoL commands

# Build our Docker Compose Image
PHONY: build
build:
	@docker-compose -f docker-compose.yml build

# Start our Docker Compose Containers
PHONY: start
start:
	@docker-compose up -d --build app

# Launch our Application (only required the first time deploying)
PHONY: launch
launch:
	@fly launch

# Deploy our Application
PHONY: deploy
deploy:
	@fly deploy

# Start composer container and run command
PHONY: composer
composer:
	@docker-compose run --rm composer $(filter-out $@,$(MAKECMDGOALS))

%:
    @:
