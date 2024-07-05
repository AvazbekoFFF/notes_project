1. git clone git@github.com:AvazbekoFFF/notes_project.git
2. cd notes_project
2. ``` docker run --rm \
   --pull=always \
   -v "$(pwd)":/opt \
   -w /opt \
   laravelsail/php82-composer:latest \
   bash -c "composer install" ```
3. configure .env (copy from env.example)
4. ```'./vendor/bin/sail up --build'```
5. cd application container, make command: 
   6. php artisan migrate, 
   7. php artisan db:seed
   8. php artisan jwt:secret
   9. php artisan scribe:generate
