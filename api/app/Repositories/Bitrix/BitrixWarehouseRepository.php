<?php

namespace App\Repositories\Bitrix;

use App\DTO\WarehouseDTO;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use App\Support\Bitrix\BitrixBoxStatusMapper;
use Illuminate\Support\Facades\DB;

final class BitrixWarehouseRepository implements WarehouseRepositoryInterface
{
    private const IBLOCK_ID       = 40;
    private const IBLOCK_GALLERY  = 43;
    private const DB_CONNECTION   = 'bitrix';

    // ID свойства STATUS боксов (из b_iblock_property WHERE iblock_id=40)
    private const PROP_STATUS = 484;

    // ID пользовательского поля UF_DISTRICT в b_user_field (ENTITY_ID = 'IBLOCK_40_SECTION').
    // Тип: enumeration — в b_uts_iblock_40_section хранится INTEGER (= b_user_field_enum.ID).
    // Текстовое значение получаем через LEFT JOIN b_user_field_enum.
    private const UF_DISTRICT_FIELD_ID = 139;

    // ------------------------------------------------------------------ //
    //  Public API                                                          //
    // ------------------------------------------------------------------ //

    public function getAll(): array
    {
        $rows = $this->fetchSections();

        $warehouseIds = array_column($rows, 'id');
        $photoMap     = $this->fetchFirstPhotoPerWarehouse($warehouseIds);
        $boxCounts    = $this->fetchBoxCounts($warehouseIds);

        return array_map(
            fn(object $row) => $this->hydrate(
                $row,
                $photoMap[$row->id] ?? null,
                [],
                $boxCounts[$row->id] ?? ['total' => 0, 'available' => 0],
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

        $photos    = $this->getPhotos($id);
        $boxCounts = $this->fetchBoxCounts([$id]);

        return $this->hydrate(
            $row,
            $photos[0] ?? null,
            $photos,
            $boxCounts[$id] ?? ['total' => 0, 'available' => 0],
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

        $id        = (int) $row->id;
        $photos    = $this->getPhotos($id);
        $boxCounts = $this->fetchBoxCounts([$id]);

        return $this->hydrate(
            $row,
            $photos[0] ?? null,
            $photos,
            $boxCounts[$id] ?? ['total' => 0, 'available' => 0],
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
    private function fetchFirstPhotoPerWarehouse(array $warehouseIds): array
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

        $firstIds = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element')
            ->whereIn('IBLOCK_SECTION_ID', $gallerySectionIds)
            ->where('IBLOCK_ID', self::IBLOCK_GALLERY)
            ->where('ACTIVE', 'Y')
            ->whereNotNull('DETAIL_PICTURE')
            ->groupBy('IBLOCK_SECTION_ID')
            ->selectRaw('IBLOCK_SECTION_ID, MIN(ID) as min_id')
            ->pluck('min_id', 'IBLOCK_SECTION_ID');

        if ($firstIds->isEmpty()) {
            return [];
        }

        $files = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as ie')
            ->join('b_file as f', 'f.ID', '=', 'ie.DETAIL_PICTURE')
            ->whereIn('ie.ID', $firstIds->values()->all())
            ->select([
                'ie.IBLOCK_SECTION_ID as gallery_section_id',
                'f.SUBDIR             as subdir',
                'f.FILE_NAME          as file_name',
            ])
            ->get()
            ->keyBy('gallery_section_id');

        $result = [];
        foreach ($gallerySections as $gallerySectionId => $mapping) {
            $file = $files->get($gallerySectionId);
            if ($file) {
                $result[$mapping->warehouse_id] = $this->buildFileUrl($file->subdir, $file->file_name);
            }
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
    private function fetchBoxCounts(array $warehouseIds): array
    {
        if (empty($warehouseIds)) {
            return [];
        }

        $rows = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as ie')
            ->join(
                'b_iblock_element_property as prop',
                fn($join) => $join
                    ->on('prop.IBLOCK_ELEMENT_ID', '=', 'ie.ID')
                    ->where('prop.IBLOCK_PROPERTY_ID', '=', self::PROP_STATUS)
            )
            ->where('ie.IBLOCK_ID', self::IBLOCK_ID)
            ->where('ie.ACTIVE', 'Y')
            ->whereIn('ie.IBLOCK_SECTION_ID', $warehouseIds)
            ->whereIn('prop.VALUE_ENUM', self::visibleStatusIds())
            ->groupBy('ie.IBLOCK_SECTION_ID')
            ->selectRaw(
                'ie.IBLOCK_SECTION_ID as warehouse_id,
                 COUNT(*) as total,
                 SUM(prop.VALUE_ENUM = ?) as available',
                [BitrixBoxStatusMapper::freeId()]
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

    // ------------------------------------------------------------------ //
    //  Private: hydration                                                  //
    // ------------------------------------------------------------------ //

    private function hydrate(
        object  $row,
        ?string $firstPhoto = null,
        array   $allPhotos  = [],
        array   $boxCounts  = ['total' => 0, 'available' => 0],
    ): WarehouseDTO {
        [$lat, $lng] = $this->parseCoords($row->map_coords ?? '');

        return new WarehouseDTO(
            id:                   (int) $row->id,
            name:                 (string) $row->name,
            code:                 (string) $row->code,
            address:              trim((string) ($row->address ?? '')),
            phone:                trim((string) ($row->phone ?? '')),
            accessHours:          trim((string) ($row->access_hours ?? '')),
            receptionHours:       trim((string) ($row->reception_hours ?? '')),
            lat:                  $lat,
            lng:                  $lng,
            metro:                $this->parseMetro($row->metro_raw ?? ''),
            pricePerSqm:          $this->parsePricePerSqm($row->price_per_sqm_raw ?? ''),
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
                array_map(fn($v) => trim((string) $v), $data),
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

        $trimmed = trim((string) $raw);

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

        $plain = trim(strip_tags($html));

        return $plain !== '' ? $plain : null;
    }
}
