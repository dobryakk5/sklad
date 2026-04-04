<?php

namespace App\Domain\Box;

enum BoxStatus: string
{
    case Free = 'free';
    case Rented = 'rented';
    case Reserved = 'reserved';
    case Freeing7 = 'freeing_7';
    case Freeing14 = 'freeing_14';

    /**
     * Бокс доступен для аренды: свободен сейчас или освобождается в ближайшие дни.
     */
    public function isAvailable(): bool
    {
        return in_array($this, [
            self::Free,
            self::Freeing7,
            self::Freeing14,
        ], true);
    }

    /**
     * Бокс показывается в публичном каталоге.
     * Служебные (service) и удалённые (deleted) статусы исключены на уровне репозитория,
     * этот метод — дополнительная защита на уровне домена.
     */
    public function isVisibleInCatalog(): bool
    {
        return in_array($this, [
            self::Free,
            self::Rented,
            self::Reserved,
            self::Freeing7,
            self::Freeing14,
        ], true);
    }
}
