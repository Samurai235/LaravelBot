setup: env-prepare key docker db-prepare #запуск нового проекта

env-prepare:
	cp -n .env.example .env

key:
	docker-compose exec app php artisan key:generate

docker:
	docker-compose up -d

db-prepare:
	docker-compose exec app php artisan migrate --seed

db-refresh:
	docker-compose exec app php artisan migrate:refresh --seed

.PHONY: docker
