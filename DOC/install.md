
cd sklad/api
php artisan migrate
php artisan operator:create admin@example.com "Admin" "strong-password" --role=admin
