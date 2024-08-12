Pre requisite


set 2 more hostname 
mypass.test     -----> front end
api.mypass.test -----> api


download docker
copy .env file
run docker compose up -d


use the following setting for env setup in backend
DB_CONNECTION=pgsql
DB_HOST=mypass_db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=postgres
DB_PASSWORD=postgres

TODO
setup minio with keys and buckets

