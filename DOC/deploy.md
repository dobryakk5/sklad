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


alter USER 'bitrix_dev'@'213.171.9.201' IDENTIFIED BY 'ploked555';
GRANT ALL PRIVILEGES ON sitemanager.* TO 'bitrix_dev'@'213.171.9.201';
FLUSH PRIVILEGES;


docker rm -f redis

docker run -d \
  --name redis \
  --restart unless-stopped \
  -p 127.0.0.1:6379:6379 \
  -v redis-data:/data \
  redis:latest \
  redis-server --appendonly yes

  