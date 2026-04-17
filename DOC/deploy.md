cd /var/www/sklad
git pull
docker compose up -d --force-recreate sklad-next
systemctl restart sklad-api

-----------
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



---- frontend /var/www/sklad/frontend

 docker run --name sklad-next --rm -it   -p 3000:3000   --add-host=host.docker.internal:host-gateway   -v "$PWD":/app   -v next_node_modules:/app/node_modules   -w /app   node:20   sh -c "npm install && npm run dev -- --hostname 0.0.0.0 --port 3000"

--- api /var/www/sklad/api

 php artisan serve --host=0.0.0.0 --port=8000




Сразу замечу: твои текущие команды для **Next** (`npm run dev`) и **Laravel** (`php artisan serve`) — это dev-режим. У Next для развёртывания рекомендуется production-режим, а `artisan serve` у Laravel — локальный development server. Для постоянной боевой схемы лучше потом перейти на `next build && next start` и на `nginx + php-fpm`. Но чтобы просто поставить текущее решение в автозагрузку, можно оставить так, как ниже. ([Next.js][2])

### 1) Включить Docker при старте системы

```bash
systemctl enable --now docker
```

### 2) Redis: пересоздать контейнер один раз правильно

```bash
docker rm -f redis 2>/dev/null || true

docker run -d \
  --name redis \
  --restart unless-stopped \
  -p 127.0.0.1:6379:6379 \
  -v redis-data:/data \
  redis:latest \
  redis-server --appendonly yes
```

### 3) Frontend: пересоздать контейнер без `--rm` и без `-it`

```bash
docker rm -f sklad-next 2>/dev/null || true

docker run -d \
  --name sklad-next \
  --restart unless-stopped \
  -p 3000:3000 \
  --add-host=host.docker.internal:host-gateway \
  -v /var/www/sklad/frontend:/app \
  -v next_node_modules:/app/node_modules \
  -w /app \
  node:20 \
  sh -c "npm install && npm run dev -- --hostname 0.0.0.0 --port 3000"
```

`--rm` здесь нужно убрать, иначе контейнер будет удаляться при остановке, а автозапускать после ребута уже будет нечего. Кроме того, `-it` для фонового сервиса не нужен. Это следует из того, как Docker работает с уже созданными контейнерами и restart policy. ([Docker Documentation][3])

### 4) API: сделать systemd unit

Создай файл `/etc/systemd/system/sklad-api.service`:

```ini
[Unit]
Description=Sklad Laravel API
After=network.target docker.service
Wants=docker.service

[Service]
Type=simple
WorkingDirectory=/var/www/sklad/api
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always
RestartSec=5
User=root

[Install]
WantedBy=multi-user.target
```

Потом включи его:

```bash
systemctl daemon-reload
systemctl enable --now sklad-api
```

Unit-файлы `systemd` как раз и кладутся в системные каталоги unit-файлов и затем включаются через `systemctl enable`. ([Red Hat Docs][4])

### 5) Проверка после настройки

```bash
docker ps
systemctl status sklad-api
docker inspect -f '{{.HostConfig.RestartPolicy.Name}}' redis
docker inspect -f '{{.HostConfig.RestartPolicy.Name}}' sklad-next
ss -ltnp | grep -E '(:3000|:6379|:8000)'
```

Если хочешь проверить именно автозапуск после ребута:

```bash
reboot
```

После входа:

```bash
docker ps
systemctl status sklad-api
```

### Что я бы поменял следующим шагом

Рабочая схема на сейчас — выше. Но для более стабильного варианта я бы потом перевёл:

* frontend: `npm run build && npm run start` вместо `npm run dev`
* api: `nginx + php-fpm` вместо `artisan serve`

