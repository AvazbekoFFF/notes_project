1. git clone ...
2. cd notes_project
2. docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php83-composer:latest \
   composer install --ignore-platform-reqs
3. configure .env (copy from env.example)
4. ./vendor/bin/sail up --d
5. cd application container, make command: 
   6. php artisan migrate, 
   7. php artisan db:seed
   8. php artisan jwt:secret
   9. php artisan scribe:generate
