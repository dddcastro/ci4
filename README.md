docker compose build
docker compose up -d

#alterar o arquivo env para .env

docker compose exec web php spark db:create ci4
docker compose exec web php spark migrate:refresh
docker compose exec web php spark db:seed Clientes