Как поднять API:

cd /Users/pavellebedev/Desktop/proj/sklad/api
composer install
php artisan key:generate

cd api && php artisan serve

Laravel после этого будет доступен обычно на http://127.0.0.1:8000.

Как поднять Redis:

если установлен через Homebrew:
brew services start redis
или вручную:
redis-server