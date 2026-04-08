<?php

namespace App\Repositories\Bitrix;

use App\DTO\BoxDTO;
use App\DTO\BoxFiltersDTO;
use App\Domain\Box\BoxStatus;
use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Support\Bitrix\BitrixBoxStatusMapper;
use App\Support\Bitrix\RentalModeMapper;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class BitrixBoxRepository implements BoxRepositoryInterface
{
    private const IBLOCK_ID     = 40;
    private const DB_CONNECTION = 'bitrix';
    private const BASE_PRICE_GROUP_ID = 1;

    // Property ID-ы из b_iblock_property WHERE iblock_id=40
    private const PROP_STATUS      = 484;
    private const PROP_SQUARE      = 480;
    private const PROP_VOLUME      = 492;
    private const PROP_FLOOR       = 479;
    private const PROP_BOX_NUMBER  = 483;
    private const PROP_CODE_1C     = 491;
    private const PROP_OBJECT_TYPE = 640;
    private const PROP_RENT_TYPE   = 481;
    private const PROP_NAME_SITE   = 495;

    // ------------------------------------------------------------------ //
    //  Public API                                                          //
    // ------------------------------------------------------------------ //

    /**
     * @return BoxDTO[]
     */
    public function getList(BoxFiltersDTO $filters): array
    {
        $enumMap = $this->loadEnumMap();
        $rows    = $this->buildFilterQuery($filters)
            ->orderBy('ie.SORT')
            ->orderBy('ie.ID')
            ->limit($filters->perPage)
            ->offset($filters->offset())
            ->get()
            ->all();

        // object_type фильтруется в PHP: JOIN с b_iblock_property_enum в SQL
        // нивелировал бы выигрыш от условной агрегации.
        if ($filters->objectType !== null) {
            $rows = array_values(array_filter(
                $rows,
                fn(object $row) => $this->resolveEnum($row->object_type_enum_id, $enumMap)
                    === $filters->objectType,
            ));
        }

        return array_values(array_filter(
            array_map(fn(object $row) => $this->hydrate($row, $enumMap), $rows),
        ));
    }

    /**
     * Полное количество боксов по фильтру — без LIMIT/OFFSET.
     * Используется для корректного meta.total в пагинированных ответах.
     */
    public function countList(BoxFiltersDTO $filters): int
    {
        // object_type фильтруется в PHP → нужно загрузить строки без LIMIT.
        if ($filters->objectType !== null) {
            $enumMap = $this->loadEnumMap();
            $rows    = $this->buildFilterQuery($filters)->get()->all();

            return count(array_filter(
                $rows,
                fn(object $row) => $this->resolveEnum($row->object_type_enum_id, $enumMap)
                    === $filters->objectType,
            ));
        }

        // Оборачиваем GROUP BY-запрос в подзапрос и считаем строки.
        // fromSub корректно сохраняет порядок биндингов selectRaw/where/having.
        $inner = $this->buildFilterQuery($filters);

        return (int) DB::connection(self::DB_CONNECTION)
            ->query()
            ->fromSub($inner, '_counted')
            ->count();
    }

    public function getById(int $id): ?BoxDTO
    {
        $row = $this->baseQuery()->where('ie.ID', $id)->first();

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row, $this->loadEnumMap());
    }

    // ------------------------------------------------------------------ //
    //  Private: query building                                             //
    // ------------------------------------------------------------------ //

    /**
     * Строит запрос со всеми фильтрами, но БЕЗ LIMIT/OFFSET/ORDER.
     * Переиспользуется в getList() и countList().
     */
    private function buildFilterQuery(BoxFiltersDTO $filters): Builder
    {
        $query = $this->baseQuery();

        if ($filters->warehouseId !== null) {
            $query->where('ie.IBLOCK_SECTION_ID', $filters->warehouseId);
        }

        if ($filters->rentalMode !== null) {
            $this->applyRentalModeHaving($query, $filters);
        }

        // Фильтр по статусу через HAVING (агрегированное поле — WHERE не работает)
        $statusIds = $filters->statusEnumIds();
        if ($statusIds !== null) {
            $query->havingRaw($this->statusHavingExpr(count($statusIds)), $statusIds);
        } else {
            $visible = BitrixBoxStatusMapper::visibleIds();
            $query->havingRaw($this->statusHavingExpr(count($visible)), $visible);
        }

        if ($filters->squareMin !== null) {
            $query->havingRaw(
                'MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ' . self::PROP_SQUARE . ' THEN ipv.VALUE_NUM END) >= ?',
                [$filters->squareMin],
            );
        }
        if ($filters->squareMax !== null) {
            $query->havingRaw(
                'MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ' . self::PROP_SQUARE . ' THEN ipv.VALUE_NUM END) <= ?',
                [$filters->squareMax],
            );
        }

        return $query;
    }

    /**
     * Базовый запрос: JOIN'ы + условная агрегация свойств.
     * Один проход по b_iblock_element_property вместо N JOIN'ов.
     */
    private function baseQuery(): Builder
    {
        return DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as ie')
            ->leftJoin('b_iblock_element_property as ipv', 'ipv.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
            ->leftJoin('b_file as f', 'f.ID', '=', 'ie.PREVIEW_PICTURE')
            ->leftJoin(
                'b_catalog_price as cp',
                fn($join) => $join
                    ->on('cp.PRODUCT_ID', '=', 'ie.ID')
                    ->where('cp.CATALOG_GROUP_ID', '=', self::BASE_PRICE_GROUP_ID)
            )
            ->leftJoin('b_uts_iblock_40_section as uf', 'uf.VALUE_ID', '=', 'ie.IBLOCK_SECTION_ID')
            ->where('ie.IBLOCK_ID', self::IBLOCK_ID)
            ->where('ie.ACTIVE', 'Y')
            ->groupBy(
                'ie.ID',
                'ie.NAME',
                'ie.IBLOCK_SECTION_ID',
                'ie.SORT',
                'f.SUBDIR',
                'f.FILE_NAME',
                'uf.UF_PRICE_ON_MAP',
            )
            ->selectRaw('
                ie.ID                                                               AS id,
                ie.NAME                                                             AS name,
                ie.IBLOCK_SECTION_ID                                                AS warehouse_id,
                ie.SORT                                                             AS sort,
                f.SUBDIR                                                            AS photo_subdir,
                f.FILE_NAME                                                         AS photo_file_name,
                MAX(cp.PRICE)                                                       AS catalog_price,
                uf.UF_PRICE_ON_MAP                                                  AS price_per_sqm_raw,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE_ENUM END)  AS status_enum_id,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE_NUM  END)  AS square,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE_NUM  END)  AS volume,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE_ENUM END)  AS floor_enum_id,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE_ENUM END)  AS object_type_enum_id,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE_ENUM END)  AS rent_type_enum_id,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE      END)  AS box_number,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE      END)  AS code_1c,
                MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ? THEN ipv.VALUE      END)  AS name_for_site
            ', [
                self::PROP_STATUS,
                self::PROP_SQUARE,
                self::PROP_VOLUME,
                self::PROP_FLOOR,
                self::PROP_OBJECT_TYPE,
                self::PROP_RENT_TYPE,
                self::PROP_BOX_NUMBER,
                self::PROP_CODE_1C,
                self::PROP_NAME_SITE,
            ]);
    }

    private function statusHavingExpr(int $count): string
    {
        $placeholders = implode(',', array_fill(0, $count, '?'));

        return $this->aggregatedEnumExpr(self::PROP_STATUS)
            . ' IN (' . $placeholders . ')';
    }

    private function aggregatedEnumExpr(int $propertyId): string
    {
        return 'MAX(CASE WHEN ipv.IBLOCK_PROPERTY_ID = ' . $propertyId . ' THEN ipv.VALUE_ENUM END)';
    }

    private function applyRentalModeHaving(Builder $query, BoxFiltersDTO $filters): void
    {
        $mode = $filters->rentalMode;
        if ($mode === null) {
            return;
        }

        $objectExpr = $this->aggregatedEnumExpr(self::PROP_OBJECT_TYPE);
        $rentExpr   = $this->aggregatedEnumExpr(self::PROP_RENT_TYPE);

        if (RentalModeMapper::isCompound($mode)) {
            $objectTypeIds = RentalModeMapper::objectTypeIdsFor($mode);
            $rentTypeIds   = RentalModeMapper::rentTypeIdsFor($mode);

            $query->havingRaw(
                '(' . $rentExpr . ' IN (' . $this->placeholders(count($rentTypeIds)) . ')'
                . ' OR '
                . $objectExpr . ' IN (' . $this->placeholders(count($objectTypeIds)) . '))',
                [...$rentTypeIds, ...$objectTypeIds],
            );

            return;
        }

        // box фильтруется по OBJECT_TYPE=416, а не по RENT_TYPE=340.
        // Иначе антресольные боксы попали бы и в box, и в storage.
        $objectTypeIds = RentalModeMapper::objectTypeIdsFor($mode);
        $query->havingRaw(
            $objectExpr . ' IN (' . $this->placeholders(count($objectTypeIds)) . ')',
            $objectTypeIds,
        );
    }

    private function placeholders(int $count): string
    {
        return implode(',', array_fill(0, $count, '?'));
    }

    // ------------------------------------------------------------------ //
    //  Private: hydration                                                  //
    // ------------------------------------------------------------------ //

    /** @param array<int, string> $enumMap */
    private function hydrate(object $row, array $enumMap): ?BoxDTO
    {
        // Маппим Bitrix enum ID → доменный BoxStatus через инфраструктурный маппер.
        // fromEnumId() возвращает null для служебных/удалённых статусов — такие боксы
        // не должны попасть сюда (фильтруются в buildFilterQuery через HAVING visibleIds),
        // но на случай рассинхронизации пропускаем их.
        $statusEnumId = $row->status_enum_id !== null ? (int) $row->status_enum_id : null;
        $status       = BitrixBoxStatusMapper::fromEnumId($statusEnumId);

        if ($status === null) {
            // Статус вне домена — скрываем из публичного каталога
            return null;
        }

        $square              = $row->square !== null ? (float) $row->square : null;
        $warehousePricePerSqm = $this->parsePricePerSqm((string) ($row->price_per_sqm_raw ?? ''));
        $catalogPrice        = $row->catalog_price !== null ? (float) $row->catalog_price : null;

        if ($catalogPrice !== null && $catalogPrice <= 0) {
            $catalogPrice = null;
        }

        $pricePerSqm = ($catalogPrice !== null && $square !== null && $square > 0)
            ? round($catalogPrice / $square, 2)
            : $warehousePricePerSqm;

        $price = $catalogPrice
            ?? (($square !== null && $warehousePricePerSqm !== null)
                ? round($square * $warehousePricePerSqm, 2)
                : null);

        $name = trim((string) ($row->name_for_site ?? ''));
        if ($name === '') {
            $name = trim((string) ($row->name ?? ''));
        }

        return new BoxDTO(
            id:          (int) $row->id,
            name:        $name,
            boxNumber:   trim((string) ($row->box_number ?? '')),
            code1c:      trim((string) ($row->code_1c ?? '')),
            warehouseId: (int) $row->warehouse_id,
            square:      $square,
            volume:      $row->volume !== null ? (float) $row->volume : null,
            status:      $status,             // BoxStatus enum, не строка
            floor:       $this->resolveEnum($row->floor_enum_id, $enumMap),
            objectType:  $this->resolveEnum($row->object_type_enum_id, $enumMap),
            rentType:    $this->resolveEnum($row->rent_type_enum_id, $enumMap),
            pricePerSqm: $pricePerSqm,
            price:       $price,
            photoUrl:    $this->buildFileUrl(
                             (string) ($row->photo_subdir    ?? ''),
                             (string) ($row->photo_file_name ?? ''),
                         ),
        );
    }

    // ------------------------------------------------------------------ //
    //  Private: enum & file helpers                                        //
    // ------------------------------------------------------------------ //

    /**
     * Все enum-значения для свойств iblock 40 — один запрос на вызов getList/getById.
     *
     * @return array<int, string>  [enum_id => label]
     */
    private function loadEnumMap(): array
    {
        return DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_property_enum')
            ->whereIn('PROPERTY_ID', [
                self::PROP_STATUS,
                self::PROP_FLOOR,
                self::PROP_OBJECT_TYPE,
                self::PROP_RENT_TYPE,
            ])
            ->pluck('VALUE', 'ID')
            ->map(fn($v) => (string) $v)
            ->all();
    }

    /** @param array<int, string> $enumMap */
    private function resolveEnum(mixed $enumId, array $enumMap): ?string
    {
        if ($enumId === null || $enumId === '') {
            return null;
        }

        return $enumMap[(int) $enumId] ?? null;
    }

    /** "1550 р/м2" → 1550.0 | "1 895 р/м2" → 1895.0 */
    private function parsePricePerSqm(string $raw): ?float
    {
        $trimmed = trim($raw);

        if ($trimmed === '') {
            return null;
        }

        $cleaned = str_replace([' ', "\xc2\xa0", ','], ['', '', '.'], $trimmed);

        if (preg_match('/(\d+(?:\.\d+)?)/', $cleaned, $m)) {
            $value = (float) $m[1];

            return $value > 0 ? $value : null;
        }

        return null;
    }

    private function buildFileUrl(string $subdir, string $fileName): ?string
    {
        if ($subdir === '' || $fileName === '') {
            return null;
        }

        return '/upload/' . ltrim($subdir, '/') . '/' . $fileName;
    }
}
