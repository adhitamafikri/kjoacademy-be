.PHONY: mysql_up mysql_down mysql_logs dev

mysql_up:
	docker compose up -d mysql

mysql_down:
	docker compose down mysql

mysql_logs:
	docker compose logs -f mysql

dev:
	php artisan serve --host=api.kjoacademy-lms.localhost --port=8000
