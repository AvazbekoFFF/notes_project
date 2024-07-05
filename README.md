## About Notes

## Install
### Deploy Locally
1. git clone git@github.com:AvazbekoFFF/notes_project.git
2. cd notes_project
3. configure .env (copy from env.example)
4. ```
   docker run --rm \
   --pull=always \
   -v "$(pwd)":/opt \
   -w /opt \
   laravelsail/php82-composer:latest \
   bash -c "composer install"
   ```
5. `./vendor/bin/sail up --build`
6. Get the Container name with application with `docker ps -a` like sample ` notes_project-laravel.test-1`
7. docker exec -it notes_project-laravel.test-1 /bin/bash
8. php artisan migrate
9. php artisan db:seed
10. php artisan jwt:secret
11. php artisan scribe:generate
