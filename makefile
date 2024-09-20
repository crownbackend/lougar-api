ENV_FILE=.env.local

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
	docker compose --env-file .env.local -f compose.prod.yaml up -d