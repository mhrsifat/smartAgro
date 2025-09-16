docker compose build 

docker compose up -d

docker compose exec app bash

cp .env.docker.example .env.docker

php artisan key:generate

php artisan storage:link

php artisan migrate