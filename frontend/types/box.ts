export type BoxStatus =
  | 'free'       // 346 — свободен
  | 'rented'     // 341 — занят
  | 'reserved'   // 347 — зарезервирован
  | 'freeing_7'  // 344 — освобождается через 7 дней
  | 'freeing_14' // 345 — освобождается через 14 дней

export type BoxFloor = 'first' | 'second' | 'third' | 'other'

export interface Box {
  id: number
  name: string
  box_number: string
  code_1c: string
  square: number | null
  volume: number | null
  floor: BoxFloor       // нормализуется на бэкенде: "1 Этаж" → "first"
  status: BoxStatus     // нормализуется на бэкенде: 346 → "free"
  object_type: string
  rent_type: string
  price_per_sqm: number | null
  price: number | null
  photo_url: string | null
  warehouse_id: number
}

export interface BoxFilters {
  status?: BoxStatus | 'available' | 'all' // all = без фильтра, available = free + freeing_7 + freeing_14
  floor?: BoxFloor
  square_min?: number
  square_max?: number
  sort?: 'price_asc' | 'price_desc' | 'square_asc' | 'square_desc'
}

/**
 * Параметры запроса GET /api/boxes
 * Имена полей соответствуют BoxListRequest на бэкенде.
 */
export interface BoxApiParams {
  stock_id?: number        // ID склада (было warehouse_id — не совпадало с бэкендом)
  status?: string          // 'free' | 'rented' | 'reserved' | 'freeing_7' | 'freeing_14'
  size_min?: number        // минимальная площадь м²
  size_max?: number        // максимальная площадь м²
  object_type?: string     // 'Бокс' | 'Ячейка' | 'Контейнер' | 'Антресольный бокс'
  page?: number
  per_page?: number
}
