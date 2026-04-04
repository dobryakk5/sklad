<?php

namespace App\Support\Bitrix;

use App\Domain\Box\BoxStatus;

final class BitrixBoxStatusMapper
{
    public static function fromEnumId(?int $enumId): ?BoxStatus
    {
        return match ($enumId) {
            self::freeId()      => BoxStatus::Free,
            self::rentedId()    => BoxStatus::Rented,
            self::reservedId()  => BoxStatus::Reserved,
            self::freeing7Id()  => BoxStatus::Freeing7,
            self::freeing14Id() => BoxStatus::Freeing14,
            default => null,
        };
    }

    /**
     * @return int[]|null
     */
    public static function toEnumIds(?string $apiStatus): ?array
    {
        if ($apiStatus === null || $apiStatus === '') {
            return null;
        }

        $status = BoxStatus::tryFrom($apiStatus);

        if ($status === null) {
            return null;
        }

        return [self::enumIdFor($status)];
    }

    /**
     * @return string[]
     */
    public static function apiValues(): array
    {
        return array_map(
            static fn (BoxStatus $status) => $status->value,
            BoxStatus::cases(),
        );
    }

    /**
     * @return int[]
     */
    public static function visibleIds(): array
    {
        return [
            self::freeId(),
            self::rentedId(),
            self::reservedId(),
            self::freeing7Id(),
            self::freeing14Id(),
        ];
    }

    /**
     * @return int[]
     */
    public static function availableIds(): array
    {
        return [
            self::freeId(),
            self::freeing7Id(),
            self::freeing14Id(),
        ];
    }

    public static function freeId(): int
    {
        return (int) config('bitrix.status_ids.free');
    }

    public static function rentedId(): int
    {
        return (int) config('bitrix.status_ids.rented');
    }

    public static function reservedId(): int
    {
        return (int) config('bitrix.status_ids.reserved');
    }

    public static function freeing7Id(): int
    {
        return (int) config('bitrix.status_ids.freeing_7');
    }

    public static function freeing14Id(): int
    {
        return (int) config('bitrix.status_ids.freeing_14');
    }

    public static function enumIdFor(BoxStatus $status): int
    {
        return match ($status) {
            BoxStatus::Free      => self::freeId(),
            BoxStatus::Rented    => self::rentedId(),
            BoxStatus::Reserved  => self::reservedId(),
            BoxStatus::Freeing7  => self::freeing7Id(),
            BoxStatus::Freeing14 => self::freeing14Id(),
        };
    }
}
