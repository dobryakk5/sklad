
**Что уже понятно:**
- Склады — секции iblock 40, данные в `b_uts_iblock_40_section` (адрес, телефон, метро, координаты из `UF_MAP`, часы из `UF_DOSTUP_TIME`)
- Боксы — элементы iblock 40, статусы через enum (Свободен=346, Арендован=341, Забронирован=347)
- CRM (аренды) — **в этой БД нет**, значит `/api/client/rentals` на этапе 1 либо редирект, либо откладываем
- Фото — через `b_file` по `preview_picture`


**Цена на бокс** — iblock 27 (тарифы) привязан к секциям iblock 27/28, а не к боксам напрямую. Цена склада — `UF_PRICE_ON_MAP` на секции (например "1550 р/м2"). Цена бокса = `SQUARE × цена_м2`. Отдельного price-property на боксе нет.

**STOCK_ID** — в БД нет явного поля для связи с 1С на уровне секции. `UF_ONE` — просто текстовая заметка. Связь с 1С идёт через `CODE_1C` на элементах (боксах).

**Фото URL** строится как: `/upload/{subdir}/{file_name}` → например `/upload/iblock/bad/baddf5dd5b060498833f26439f92e822.jpg`

**CRM** — в этой БД нет совсем. `/api/client/rentals` в этапе 1 = редирект на Битрикс, как и указано в матрице.

---

**Итоговая схема репозиториев:**

```
BitrixWarehouseRepository
  источник: b_iblock_section + b_uts_iblock_40_section
  поля:     id, name, code, address (UF_ADDRESS), phone (UF_PHONE),
            metro (UF_METRO, unserialize), coords (UF_MAP → lat/lng),
            access_hours (UF_DOSTUP_TIME), reception_hours (UF_RECEPTION),
            price_per_sqm (UF_PRICE_ON_MAP, strip "р/м2")

BitrixBoxRepository
  источник: b_iblock_element
          + b_iblock_element_property (пивот по property_id)
          + b_iblock_property_enum   (расшифровка L-полей)
          + b_file                   (фото)
  поля:     id, name (NAME_FOR_SITE → fallback name),
            box_number (BOX_NUMBER), code_1c (CODE_1C),
            square (SQUARE), volume (VOLUME),
            floor (FLOOR enum), status (STATUS enum → 346/341/347),
            object_type (OBJECT_TYPE enum),
            rent_type (RENT_TYPE enum),
            price_per_sqm (из секции), price (square × price_per_sqm),
            photo_url (/upload/subdir/file_name),
            warehouse_id (iblock_section_id)
  фильтры:  status, warehouse_id, square range
```

**Важные ID для хардкода в репозитории:**

```php
// STATUS enum
const STATUS_FREE     = 346;
const STATUS_RENTED   = 341;
const STATUS_RESERVED = 347;
const STATUS_FREEING_7  = 344;
const STATUS_FREEING_14 = 345;

// PROPERTY IDs
const PROP_STATUS    = 484;
const PROP_SQUARE    = 480;
const PROP_FLOOR     = 479;
const PROP_BOX_NUM   = 483;
const PROP_CODE_1C   = 491;
const PROP_OBJECT_TYPE = 640;
const PROP_RENT_TYPE = 481;
const PROP_NAME_SITE = 495;
const PROP_VOLUME    = 492;

// IBLOCK
const IBLOCK_BOXES = 40;
```


Связь кристально чистая:
b_uts_iblock_40_section.UF_PHOTOGALLERY (63→109, 64→110...)
    → b_iblock_section.id (iblock 43)
        → b_iblock_element.iblock_section_id
            → detail_picture → b_file

План laravel_bitrix_repository_architecture.svg

Схема даёт полную картину. Теперь предлагаю такой порядок написания кода:
1. Interfaces — BoxRepositoryInterface, WarehouseRepositoryInterface
2. BitrixWarehouseRepository — самый простой, два JOIN'а (b_iblock_section + b_uts_iblock_40_section), плюс галерея через UF_PHOTOGALLERY → iblock_element → b_file
3. BitrixBoxRepository — сложнее: пивот по свойствам, расшифровка enum, цена через секцию
4. Контроллеры + роуты — GET /api/warehouses, /api/boxes, /api/boxes/{id}
5. Redis кэш — обёртки в репозиториях