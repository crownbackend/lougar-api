ENV_FILE=.env.local

up:
	docker compose --env-file ${ENV_FILE} up -d
down:
	docker compose --env-file ${ENV_FILE} down
logs:
	docker compose --env-file ${ENV_FILE} logs
api:
	docker exec -it  api-php-fpm-1 bash
db:
	docker exec -it api-database-1 bash