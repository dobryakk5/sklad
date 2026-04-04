<?php

namespace App\Console\Commands;

use App\Support\Cache\CacheKeys;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

/**
 * Ручной сброс Redis-кэша Битрикс-данных.
 *
 * php artisan bitrix:cache:clear           — все ключи
 * php artisan bitrix:cache:clear --warehouses
 * php artisan bitrix:cache:clear --boxes
 */
class ClearBitrixCacheCommand extends Command
{
    protected $signature = 'bitrix:cache:clear
                            {--warehouses : Только ключи складов}
                            {--boxes      : Только ключи боксов}';

    protected $description = 'Сбросить Redis-кэш данных Битрикс (склады и/или боксы)';

    public function handle(): int
    {
        $clearWarehouses = $this->option('warehouses') || (!$this->option('warehouses') && !$this->option('boxes'));
        $clearBoxes      = $this->option('boxes')      || (!$this->option('warehouses') && !$this->option('boxes'));

        if ($clearWarehouses) {
            $count = $this->flushByPattern(CacheKeys::warehousePattern());
            $this->info("Склады: удалено {$count} ключей");
        }

        if ($clearBoxes) {
            $count = $this->flushByPattern(CacheKeys::boxPattern());
            $this->info("Боксы: удалено {$count} ключей");
        }

        $this->info('Готово.');

        return self::SUCCESS;
    }

    /**
     * Удаляет ключи по glob-паттерну через SCAN.
     * SCAN безопасен для production — не блокирует Redis в отличие от KEYS.
     *
     * ВАЖНО: Laravel добавляет к каждому ключу префикс из config('cache.prefix').
     * Паттерн из CacheKeys (например 'bitrix:warehouses:*') должен быть дополнен
     * этим префиксом, иначе SCAN не найдёт ни одного ключа.
     *
     * Реальный Redis-ключ: {prefix}:{app_name}:bitrix:warehouses:all
     * Laravel использует формат: {CACHE_PREFIX}_{APP_NAME}:key
     * Поэтому безопаснее всего искать по суффиксу через '*{pattern}'.
     */
    private function flushByPattern(string $pattern): int
    {
        $redis   = Redis::connection('cache')->client();
        $deleted = 0;
        $cursor  = '0';

        // Префикс из config + символ разделителя, который использует Laravel Cache.
        // config('cache.prefix') уже содержит значение без trailing-двоеточия.
        $prefix        = config('cache.prefix');
        $fullPattern   = $prefix ? "{$prefix}:{$pattern}" : $pattern;

        do {
            [$cursor, $keys] = $redis->scan($cursor, ['MATCH' => $fullPattern, 'COUNT' => 200]);

            if (!empty($keys)) {
                $redis->del(...$keys);
                $deleted += count($keys);
            }
        } while ($cursor !== '0');

        return $deleted;
    }
}
