# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Alfasklad** — a self-storage rental platform. The system integrates with an external **Bitrix CMS** as the source of truth for warehouses, boxes, and client data. Laravel acts as an API proxy/cache layer; it does not own warehouse/box data in its own DB.

### Architecture

```
Bitrix CMS (MySQL: sitemanager) ←→ Laravel API (api/) ←→ Next.js Frontend (frontend/)
```

- **`api/`** — Laravel 13 / PHP 8.4 REST API
- **`frontend/`** — Turborepo monorepo with two Next.js apps:
  - `apps/site/` — public-facing website
  - `apps/cabinet/` — client personal cabinet
  - `packages/api-client/` — shared TypeScript API client used by both apps
- **`Bitrix/`** — PHP snippets/scripts for the Bitrix side (not deployed via this repo)
- **`DB/`** — schema documentation and architecture diagrams

---

## Backend (api/)

### Running locally

```bash
cd api
composer install
php artisan key:generate
php artisan serve                      # runs on http://127.0.0.1:8000
```

Redis is required for caching. Start it via Docker:

```bash
docker run -d --name redis --restart unless-stopped \
  -p 127.0.0.1:6379:6379 -v redis-data:/data \
  redis:latest redis-server --appendonly yes
```

### Testing

```bash
cd api
php artisan test --compact                        # run all tests
php artisan test --compact --filter=TestName      # run specific test
php artisan make:test --pest FeatureName          # create a new feature test
php artisan make:test --pest --unit UnitName      # create a unit test
```

### Code style

After modifying any PHP files:

```bash
cd api
vendor/bin/pint --dirty --format agent
```

### Key artisan commands

```bash
php artisan operator:create admin@example.com "Name" "password" --role=admin
php artisan bitrix:cache:clear --boxes       # clear box cache in Redis
php artisan route:list --path=api            # inspect routes
```

### Data flow: Bitrix integration

Controllers inject repository interfaces. The DI container (in `AppServiceProvider`) wires:

```
WarehouseRepositoryInterface → CachedWarehouseRepository → BitrixWarehouseRepository
BoxRepositoryInterface       → CachedBoxRepository       → BitrixBoxRepository
```

`BitrixBoxRepository` and `BitrixWarehouseRepository` query the **`bitrix` DB connection** (read-only MySQL, database `sitemanager`) directly via `DB::connection('bitrix')`. They do **not** use Eloquent models — they build raw queries against Bitrix's `b_iblock_element`, `b_iblock_section`, `b_iblock_element_property`, etc.

Cache TTL is 5 minutes (Redis). To bypass cache, swap the binding in `AppServiceProvider` to the raw Bitrix repository.

### Two database connections

- **`mysql`** — Laravel's own DB (PostgreSQL in production via Docker, MySQL for local dev). Stores: `operators`, `reviews`, `seo_meta`, `users`, password reset tokens.
- **`bitrix`** — Read-only connection to Bitrix's MySQL (`sitemanager`). Stores warehouses, boxes, clients, contracts, invoices.

### Auth

- **Admin panel** (`/api/admin/*`) — session-based auth via `admin.auth` middleware, stored in Laravel's own DB (`operators` table). Role separation via `admin.role` middleware (only admins can manage operators).
- **Client cabinet** (`/api/cabinet/*`) — proxied to Bitrix via `BitrixCabinetAuthClient`. Sessions stored server-side (Laravel session). The `cabinet.auth` middleware validates the Bitrix session on each request.

---

## Frontend (frontend/)

### Running locally

```bash
cd frontend
npm install
npm run dev          # starts all apps via Turborepo
```

Or run a single app:

```bash
cd frontend/apps/site
npm run dev
```

### Build & lint

```bash
cd frontend
npm run build        # build all apps
npm run lint         # lint all apps
npm run typecheck    # typecheck all apps
```

### Monorepo structure

- `apps/site` — Next.js 14 (App Router), public site
- `apps/cabinet` — Next.js 14 (App Router), client cabinet
- `packages/api-client` — shared typed API client (`@alfasklad/api-client`); both apps import it

The API client in `packages/api-client/src/` exports typed fetch wrappers around the Laravel API. When adding new API endpoints, update the client here.

### Environment variables

- `apps/site` needs `API_BASE_URL` (server-side, e.g. `http://127.0.0.1:8000/api`) and `NEXT_PUBLIC_API_URL` (client-side)
- `apps/cabinet` has its own `.env.local`

---

## Docker (production-like)

`docker-compose.yml` at the repo root defines: `postgres`, `redis`, and `sklad-next` (Next.js). Laravel runs as a systemd service (`php artisan serve --host=0.0.0.0 --port=8000`).

Deploy: `git pull && docker compose up -d --force-recreate sklad-next && systemctl restart sklad-api`

---

## Bitrix data model (key constants)

The Bitrix iblock structure is hardcoded in repositories — do not change without verifying against the live Bitrix DB:

- Boxes iblock ID: **40**
- Contracts iblock: **52**, Invoices: **53**, Payment methods: **69**
- Box property IDs: STATUS=484, SQUARE=480, VOLUME=492, FLOOR=479, BOX_NUMBER=483, CODE_1C=491, OBJECT_TYPE=640, RENT_TYPE=481, NAME_SITE=495
- Box status enum IDs: free=346, rented=341, reserved=347, freeing_7=344, freeing_14=345
