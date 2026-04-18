<?php

namespace App\Repositories\Bitrix;

use App\DTO\WarehouseFiltersDTO;
use App\DTO\WarehouseDTO;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use App\Support\Bitrix\BitrixBoxStatusMapper;
use App\Support\Bitrix\RentalModeMapper;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class BitrixWarehouseRepository implements WarehouseRepositoryInterface
{
    private const IBLOCK_ID       = 40;
    private const IBLOCK_GALLERY  = 43;
    private const DB_CONNECTION   = 'bitrix';
    private const BASE_PRICE_GROUP_ID = 1;

    // ID свойства STATUS боксов (из b_iblock_property WHERE iblock_id=40)
    private const PROP_STATUS      = 484;
    private const PROP_SQUARE      = 480;
    private const PROP_OBJECT_TYPE = 640;
    private const PROP_RENT_TYPE   = 481;

    // ID пользовательского поля UF_DISTRICT в b_user_field (ENTITY_ID = 'IBLOCK_40_SECTION').
    // Тип: enumeration — в b_uts_iblock_40_section хранится INTEGER (= b_user_field_enum.ID).
    // Текстовое значение получаем через LEFT JOIN b_user_field_enum.
    private const UF_DISTRICT_FIELD_ID = 139;

    // ------------------------------------------------------------------ //
    //  Public API                                                          //
    // ------------------------------------------------------------------ //

    public function getAll(WarehouseFiltersDTO $filters): array
    {
        $rows = $this->fetchSections();

        $warehouseIds = array_column($rows, 'id');
        $previewPhotos = $this->fetchPreviewPhotosPerWarehouse($warehouseIds);
        $boxCounts    = $this->fetchBoxCounts($warehouseIds, $filters);
        $priceMap     = $this->fetchFallbackPricePerSqm($warehouseIds, $filters);

        if ($filters->rentalMode !== null) {
            $rows = array_values(array_filter(
                $rows,
                fn(object $row) => ($boxCounts[$row->id]['total'] ?? 0) > 0,
            ));
        }

        return array_map(
            fn(object $row) => $this->hydrate(
                $row,
                $previewPhotos[$row->id][0] ?? null,
                $previewPhotos[$row->id] ?? [],
                $boxCounts[$row->id] ?? ['total' => 0, 'available' => 0],
                $priceMap[$row->id] ?? null,
            ),
            $rows
        );
    }

    public function getById(int $id): ?WarehouseDTO
    {
        $row = $this->fetchSections($id)[0] ?? null;

        if ($row === null) {
            return null;
        }

        $filters   = new WarehouseFiltersDTO();
        $photos    = $this->getPhotos($id);
        $boxCounts = $this->fetchBoxCounts([$id], $filters);
        $priceMap  = $this->fetchFallbackPricePerSqm([$id], $filters);

        return $this->hydrate(
            $row,
            $photos[0] ?? null,
            $photos,
            $boxCounts[$id] ?? ['total' => 0, 'available' => 0],
            $priceMap[$id] ?? null,
        );
    }

    /**
     * Поиск по строковому коду/слагу секции.
     * Фронт вызывает GET /api/warehouses/{slug} — slug = b_iblock_section.CODE.
     */
    public function getByCode(string $code): ?WarehouseDTO
    {
        $row = $this->fetchSectionByCode($code);

        if ($row === null) {
            return null;
        }

        $filters   = new WarehouseFiltersDTO();
        $id        = (int) $row->id;
        $photos    = $this->getPhotos($id);
        $boxCounts = $this->fetchBoxCounts([$id], $filters);
        $priceMap  = $this->fetchFallbackPricePerSqm([$id], $filters);

        return $this->hydrate(
            $row,
            $photos[0] ?? null,
            $photos,
            $boxCounts[$id] ?? ['total' => 0, 'available' => 0],
            $priceMap[$id] ?? null,
        );
    }

    /**
     * Фотогалерея склада — массив URL /upload/subdir/file_name.
     * Источник: UF_PHOTOGALLERY → b_iblock_element (iblock 43) → b_file.
     */
    public function getPhotos(int $warehouseId): array
    {
        $gallerySection = DB::connection(self::DB_CONNECTION)
            ->table('b_uts_iblock_40_section')
            ->where('VALUE_ID', $warehouseId)
            ->value('UF_PHOTOGALLERY');

        if (!$gallerySection) {
            return [];
        }

        $files = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as ie')
            ->join('b_file as f', 'f.ID', '=', 'ie.DETAIL_PICTURE')
            ->where('ie.IBLOCK_ID', self::IBLOCK_GALLERY)
            ->where('ie.IBLOCK_SECTION_ID', $gallerySection)
            ->where('ie.ACTIVE', 'Y')
            ->select(['f.SUBDIR', 'f.FILE_NAME'])
            ->orderBy('ie.SORT')
            ->orderBy('ie.ID')
            ->get();

        return $files
            ->map(fn(object $f) => $this->buildFileUrl($f->SUBDIR, $f->FILE_NAME))
            ->filter()
            ->values()
            ->all();
    }

    // ------------------------------------------------------------------ //
    //  Private: DB queries                                                 //
    // ------------------------------------------------------------------ //

    /** @return object[] */
    private function fetchSections(?int $id = null): array
    {
        $query = $this->baseSelectQuery();

        if ($id !== null) {
            $query->where('s.ID', $id);
        } else {
            $query->orderBy('s.SORT')->orderBy('s.ID');
        }

        return $query->get()->all();
    }

    private function fetchSectionByCode(string $code): ?object
    {
        return $this->baseSelectQuery()
            ->where('s.CODE', $code)
            ->first();
    }

    /** Общий SELECT для обоих методов получения секций. */
    private function baseSelectQuery()
    {
        return DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_section as s')
            ->join('b_uts_iblock_40_section as uf', 'uf.VALUE_ID', '=', 's.ID')
            // UF_DISTRICT — тип enumeration (USER_FIELD_ID = 139 из b_user_field).
            // В b_uts_iblock_40_section хранится INTEGER — ID записи в b_user_field_enum.
            // LEFT JOIN чтобы склады без района не пропадали из выборки.
            ->leftJoin(
                'b_user_field_enum as district_enum',
                fn ($join) => $join
                    ->on('district_enum.ID', '=', 'uf.UF_DISTRICT')
                    ->where('district_enum.USER_FIELD_ID', '=', self::UF_DISTRICT_FIELD_ID)
            )
            ->where('s.IBLOCK_ID', self::IBLOCK_ID)
            ->where('s.ACTIVE', 'Y')
            ->select([
                's.ID          as id',
                's.NAME        as name',
                's.CODE        as code',
                's.DESCRIPTION as description',
                'uf.UF_ADDRESS       as address',
                'uf.UF_PHONE         as phone',
                'uf.UF_DOSTUP_TIME   as access_hours',
                'uf.UF_RECEPTION     as reception_hours',
                'uf.UF_MAP           as map_coords',
                'uf.UF_METRO         as metro_raw',
                'uf.UF_PRICE_ON_MAP  as price_per_sqm_raw',
                'uf.UF_PHOTOGALLERY  as gallery_section_id',
                // Текстовое значение района из таблицы enum-значений
                'district_enum.VALUE as district',
            ]);
    }

    /**
     * Первое фото для каждого склада одним запросом (без N+1).
     *
     * @param  int[]  $warehouseIds
     * @return array<int, string>  warehouseId → url
     */
    private function fetchPreviewPhotosPerWarehouse(array $warehouseIds, int $limit = 3): array
    {
        if (empty($warehouseIds)) {
            return [];
        }

        $gallerySections = DB::connection(self::DB_CONNECTION)
            ->table('b_uts_iblock_40_section')
            ->whereIn('VALUE_ID', $warehouseIds)
            ->whereNotNull('UF_PHOTOGALLERY')
            ->where('UF_PHOTOGALLERY', '!=', '')
            ->select(['VALUE_ID as warehouse_id', 'UF_PHOTOGALLERY as gallery_section_id'])
            ->get()
            ->keyBy('gallery_section_id');

        if ($gallerySections->isEmpty()) {
            return [];
        }

        $gallerySectionIds = $gallerySections->keys()->all();

        $files = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as ie')
            ->join('b_file as f', 'f.ID', '=', 'ie.DETAIL_PICTURE')
            ->whereIn('IBLOCK_SECTION_ID', $gallerySectionIds)
            ->where('ie.IBLOCK_ID', self::IBLOCK_GALLERY)
            ->where('ie.ACTIVE', 'Y')
            ->whereNotNull('ie.DETAIL_PICTURE')
            ->select([
                'ie.IBLOCK_SECTION_ID as gallery_section_id',
                'f.SUBDIR             as subdir',
                'f.FILE_NAME          as file_name',
            ])
            ->orderBy('ie.IBLOCK_SECTION_ID')
            ->orderBy('ie.SORT')
            ->orderBy('ie.ID')
            ->get();

        $result = [];
        foreach ($files as $file) {
            $mapping = $gallerySections->get($file->gallery_section_id);
            if (!$mapping) {
                continue;
            }

            $warehouseId = (int) $mapping->warehouse_id;
            $photoUrl    = $this->buildFileUrl($file->subdir, $file->file_name);

            if ($photoUrl === null) {
                continue;
            }

            $result[$warehouseId] ??= [];

            if (count($result[$warehouseId]) >= $limit) {
                continue;
            }

            $result[$warehouseId][] = $photoUrl;
        }

        return $result;
    }

    /**
     * Считает боксы по складам одним запросом.
     * Возвращает ['total' => int, 'available' => int] для каждого warehouse_id.
     *
     * @param  int[]  $warehouseIds
     * @return array<int, array{total: int, available: int}>
     */
    private function fetchBoxCounts(array $warehouseIds, WarehouseFiltersDTO $filters): array
    {
        if (empty($warehouseIds)) {
            return [];
        }

        $availableStatusIds = BitrixBoxStatusMapper::availableIds();

        $query = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as ie')
            ->join(
                'b_iblock_element_property as status_prop',
                fn($join) => $join
                    ->on('status_prop.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
                    ->where('status_prop.IBLOCK_PROPERTY_ID', '=', self::PROP_STATUS)
            )
            ->where('ie.IBLOCK_ID', self::IBLOCK_ID)
            ->where('ie.ACTIVE', 'Y')
            ->whereIn('ie.IBLOCK_SECTION_ID', $warehouseIds)
            ->whereIn('status_prop.VALUE_ENUM', self::visibleStatusIds());

        $this->applyRentalModeRowFilter($query, $filters);

        $rows = $query
            ->groupBy('ie.IBLOCK_SECTION_ID')
            ->selectRaw(
                'ie.IBLOCK_SECTION_ID as warehouse_id,
                 COUNT(*) as total,
                 SUM(CASE WHEN status_prop.VALUE_ENUM IN (?, ?, ?) THEN 1 ELSE 0 END) as available',
                $availableStatusIds
            )
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row->warehouse_id] = [
                'total'     => (int) $row->total,
                'available' => (int) $row->available,
            ];
        }

        return $result;
    }

    /**
     * Возвращает fallback-цену м² из реальных catalog prices боксов, если у склада пуст UF_PRICE_ON_MAP.
     *
     * @param  int[]  $warehouseIds
     * @return array<int, float>
     */
    private function fetchFallbackPricePerSqm(array $warehouseIds, WarehouseFiltersDTO $filters): array
    {
        if (empty($warehouseIds)) {
            return [];
        }

        $query = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as ie')
            ->join(
                'b_iblock_element_property as status_prop',
                fn($join) => $join
                    ->on('status_prop.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
                    ->where('status_prop.IBLOCK_PROPERTY_ID', '=', self::PROP_STATUS)
            )
            ->join(
                'b_iblock_element_property as square_prop',
                fn($join) => $join
                    ->on('square_prop.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
                    ->where('square_prop.IBLOCK_PROPERTY_ID', '=', self::PROP_SQUARE)
            )
            ->join(
                'b_catalog_price as cp',
                fn($join) => $join
                    ->on('cp.PRODUCT_ID', '=', 'ie.ID')
                    ->where('cp.CATALOG_GROUP_ID', '=', self::BASE_PRICE_GROUP_ID)
            )
            ->where('ie.IBLOCK_ID', self::IBLOCK_ID)
            ->where('ie.ACTIVE', 'Y')
            ->whereIn('ie.IBLOCK_SECTION_ID', $warehouseIds)
            ->whereIn('status_prop.VALUE_ENUM', self::visibleStatusIds())
            ->where('square_prop.VALUE_NUM', '>', 0)
            ->where('cp.PRICE', '>', 0);

        $this->applyRentalModeRowFilter($query, $filters);

        $rows = $query
            ->groupBy('ie.IBLOCK_SECTION_ID')
            ->selectRaw(
                'ie.IBLOCK_SECTION_ID as warehouse_id, MIN(cp.PRICE / square_prop.VALUE_NUM) as price_per_sqm'
            )
            ->get();

        $result = [];
        foreach ($rows as $row) {
            if ($row->price_per_sqm === null) {
                continue;
            }

            $result[(int) $row->warehouse_id] = round((float) $row->price_per_sqm, 2);
        }

        return $result;
    }

    /**
     * @return int[]
     */
    private static function visibleStatusIds(): array
    {
        return [
            BitrixBoxStatusMapper::freeId(),
            BitrixBoxStatusMapper::rentedId(),
            BitrixBoxStatusMapper::reservedId(),
            BitrixBoxStatusMapper::freeing7Id(),
            BitrixBoxStatusMapper::freeing14Id(),
        ];
    }

    private function applyRentalModeRowFilter(Builder $query, WarehouseFiltersDTO $filters): void
    {
        $mode = $filters->rentalMode;
        if ($mode === null) {
            return;
        }

        if (RentalModeMapper::isCompound($mode)) {
            $objectTypeIds = RentalModeMapper::objectTypeIdsFor($mode);
            $rentTypeIds   = RentalModeMapper::rentTypeIdsFor($mode);

            $query
                ->leftJoin(
                    'b_iblock_element_property as object_type_prop',
                    fn($join) => $join
                        ->on('object_type_prop.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
                        ->where('object_type_prop.IBLOCK_PROPERTY_ID', '=', self::PROP_OBJECT_TYPE)
                )
                ->leftJoin(
                    'b_iblock_element_property as rent_type_prop',
                    fn($join) => $join
                        ->on('rent_type_prop.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
                        ->where('rent_type_prop.IBLOCK_PROPERTY_ID', '=', self::PROP_RENT_TYPE)
                )
                ->where(function (Builder $inner) use ($objectTypeIds, $rentTypeIds) {
                    $inner
                        ->whereIn('object_type_prop.VALUE_ENUM', $objectTypeIds)
                        ->orWhereIn('rent_type_prop.VALUE_ENUM', $rentTypeIds);
                });

            return;
        }

        $query->join(
            'b_iblock_element_property as object_type_prop',
            fn($join) => $join
                ->on('object_type_prop.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
                ->where('object_type_prop.IBLOCK_PROPERTY_ID', '=', self::PROP_OBJECT_TYPE)
        )
        ->whereIn('object_type_prop.VALUE_ENUM', RentalModeMapper::objectTypeIdsFor($mode));
    }

    // ------------------------------------------------------------------ //
    //  Private: hydration                                                  //
    // ------------------------------------------------------------------ //

    private function hydrate(
        object  $row,
        ?string $firstPhoto = null,
        array   $allPhotos  = [],
        array   $boxCounts  = ['total' => 0, 'available' => 0],
        ?float  $fallbackPricePerSqm = null,
    ): WarehouseDTO {
        [$lat, $lng] = $this->parseCoords($row->map_coords ?? '');
        $pricePerSqm = $this->parsePricePerSqm($row->price_per_sqm_raw ?? '') ?? $fallbackPricePerSqm;

        return new WarehouseDTO(
            id:                   (int) $row->id,
            name:                 $this->cleanText($row->name ?? ''),
            code:                 (string) $row->code,
            address:              $this->cleanText($row->address ?? ''),
            phone:                $this->cleanText($row->phone ?? ''),
            accessHours:          $this->cleanText($row->access_hours ?? ''),
            receptionHours:       $this->cleanText($row->reception_hours ?? ''),
            lat:                  $lat,
            lng:                  $lng,
            metro:                $this->parseMetro($row->metro_raw ?? ''),
            pricePerSqm:          $pricePerSqm,
            description:          $this->stripHtml($row->description ?? ''),
            photos:               $allPhotos ?: ($firstPhoto ? [$firstPhoto] : []),
            district:             $this->parseDistrict($row->district ?? null),
            availableBoxesCount:  $boxCounts['available'],
            totalBoxesCount:      $boxCounts['total'],
        );
    }

    // ------------------------------------------------------------------ //
    //  Private: parsers                                                    //
    // ------------------------------------------------------------------ //

    /** "55.779030,37.865413" → [55.779030, 37.865413] */
    private function parseCoords(string $raw): array
    {
        if ($raw === '') {
            return [null, null];
        }

        $parts = explode(',', $raw, 2);

        if (count($parts) !== 2) {
            return [null, null];
        }

        $lat = filter_var(trim($parts[0]), FILTER_VALIDATE_FLOAT);
        $lng = filter_var(trim($parts[1]), FILTER_VALIDATE_FLOAT);

        return [
            $lat !== false ? $lat : null,
            $lng !== false ? $lng : null,
        ];
    }

    /**
     * PHP-serialized строка из Битрикса → array of strings.
     * a:3:{i:0;s:20:"Новокосино";...} → ['Новокосино', ...]
     */
    private function parseMetro(string $raw): array
    {
        if ($raw === '') {
            return [];
        }

        $data = @unserialize($raw);

        if (!is_array($data)) {
            return [];
        }

        return array_values(
            array_filter(
                array_map(fn($v) => $this->cleanText($v), $data),
                fn($v) => $v !== ''
            )
        );
    }

    /**
     * "1550 р/м2" → 1550.0
     * Извлекаем первое число из строки.
     */
    private function parsePricePerSqm(string $raw): ?float
    {
        $trimmed = trim($raw);

        if ($trimmed === '') {
            return null;
        }

        // Убираем пробелы-разделители тысяч и нормализуем запятую → точка
        $cleaned = str_replace([' ', "\xc2\xa0", ','], ['', '', '.'], $trimmed);

        // Берём первое число (целое или дробное)
        if (preg_match('/(\d+(?:\.\d+)?)/', $cleaned, $m)) {
            return (float) $m[1];
        }

        return null;
    }

    /**
     * Нормализует значение района: пустая строка → null.
     */
    private function parseDistrict(mixed $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $trimmed = $this->cleanText($raw);

        return $trimmed !== '' ? $trimmed : null;
    }

    /** /upload/{subdir}/{file_name} */
    private function buildFileUrl(string $subdir, string $fileName): ?string
    {
        if ($subdir === '' || $fileName === '') {
            return null;
        }

        return '/upload/' . ltrim($subdir, '/') . '/' . $fileName;
    }

    private function stripHtml(string $html): ?string
    {
        if ($html === '') {
            return null;
        }

        $plain = $this->cleanText(strip_tags($html));

        return $plain !== '' ? $plain : null;
    }

    private function cleanText(mixed $raw): string
    {
        $text = preg_replace('/&nbsp;?/iu', ' ', (string) $raw) ?? (string) $raw;
        $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = str_replace("\u{00A0}", ' ', $decoded);

        return trim($normalized);
    }
}
