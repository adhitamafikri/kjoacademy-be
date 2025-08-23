.PHONY: mysql_up mysql_down mysql_logs dev route_list seed_fresh

mysql_up:
	docker compose up -d mysql

mysql_down:
	docker compose down mysql

mysql_logs:
	docker compose logs -f mysql

dev:
	php artisan serve --host=api.kjoacademy-lms.localhost --port=8000

route_list:
	php artisan route:list

seed_fresh:
	@php artisan migrate:fresh --seed
	@php artisan passport:client --personal --name="KJO Academy LMS Client" --provider="users"
