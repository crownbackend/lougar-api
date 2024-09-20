ENV_FILE=.env.local
COMPOSE_FILE=compose-prod.yaml

up:
	docker compose --env-file ${ENV_FILE} up -d
rebuild:
	docker compose --env-file ${ENV_FILE} up -d --build
down:
	docker compose --env-file ${ENV_FILE} down
logs:
	docker compose --env-file ${ENV_FILE} logs
api:
	docker exec -it  api-php-fpm-1 bash
db:
	docker exec -it api-database-1 bash

prod:
	docker compose -f $(COMPOSE_FILE) --env-file $(ENV_FILE) up -d