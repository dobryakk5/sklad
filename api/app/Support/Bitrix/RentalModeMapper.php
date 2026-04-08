<?php

namespace App\Support\Bitrix;

use App\Domain\Box\RentalMode;

final class RentalModeMapper
{
    private const RENT_TYPE_OFFICE = 337;

    private const OBJECT_TYPE_STORAGE   = 413; // Антресольный бокс
    private const OBJECT_TYPE_CELL      = 414; // Ячейка
    private const OBJECT_TYPE_CONTAINER = 415; // Контейнер
    private const OBJECT_TYPE_BOX       = 416; // Обычный бокс

    /**
     * @return string[]
     */
    public static function apiValues(): array
    {
        return array_map(
            static fn (RentalMode $mode) => $mode->value,
            RentalMode::cases(),
        );
    }

    /**
     * box намеренно маппится на OBJECT_TYPE=Бокс, а не на RENT_TYPE=Аренда бокса.
     * Иначе антресольные боксы (кладовки) пересекались бы с обычными боксами.
     *
     * @return int[]
     */
    public static function objectTypeIdsFor(RentalMode $mode): array
    {
        return match ($mode) {
            RentalMode::Box       => [self::OBJECT_TYPE_BOX],
            RentalMode::Container => [self::OBJECT_TYPE_CONTAINER],
            RentalMode::Cell      => [self::OBJECT_TYPE_CELL],
            RentalMode::Storage   => [self::OBJECT_TYPE_STORAGE],
            RentalMode::Room      => [
                self::OBJECT_TYPE_BOX,
                self::OBJECT_TYPE_CONTAINER,
                self::OBJECT_TYPE_STORAGE,
            ],
        };
    }

    /**
     * @return int[]
     */
    public static function rentTypeIdsFor(RentalMode $mode): array
    {
        return match ($mode) {
            RentalMode::Room => [self::RENT_TYPE_OFFICE],
            default => [],
        };
    }

    public static function isCompound(RentalMode $mode): bool
    {
        return $mode === RentalMode::Room;
    }
}
