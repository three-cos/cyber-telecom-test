DOCKER_COMPOSE_FLAGS=-f docker-compose.yml -f docker-compose.override.yml

up:
	docker-compose ${DOCKER_COMPOSE_FLAGS} up -d --build

down:
	docker-compose ${DOCKER_COMPOSE_FLAGS} down

cli:
	docker-compose ${DOCKER_COMPOSE_FLAGS} exec --user=web app bash || true

local_logs:
	docker-compose ${DOCKER_COMPOSE_FLAGS} exec --user=web app tail -n 100 -f ./storage/logs/laravel.log