<?php

namespace App\Providers;

use App\Repositories\Bitrix\BitrixBoxRepository;
use App\Repositories\Bitrix\BitrixWarehouseRepository;
use App\Repositories\Cached\CachedBoxRepository;
use App\Repositories\Cached\CachedWarehouseRepository;
use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bitrix-репозитории — читают напрямую из MySQL
        $this->app->bind(BitrixWarehouseRepository::class);
        $this->app->bind(BitrixBoxRepository::class);

        // Публичные биндинги — кэш-декораторы поверх Bitrix
        // Контроллеры инжектируют интерфейс и получают кэшированную версию.
        // Для отключения кэша — поменять на BitrixWarehouseRepository::class здесь.
        $this->app->bind(
            WarehouseRepositoryInterface::class,
            fn($app) => new CachedWarehouseRepository(
                $app->make(BitrixWarehouseRepository::class)
            ),
        );

        $this->app->bind(
            BoxRepositoryInterface::class,
            fn($app) => new CachedBoxRepository(
                $app->make(BitrixBoxRepository::class)
            ),
        );
    }

    public function boot(): void {}
}
