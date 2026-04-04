export interface Warehouse {
  id: number
  name: string
  slug: string              // = b_iblock_section.CODE, используется в URL
  district: string          // из UF_DISTRICT (enumeration); может быть пустой строкой
  address: string
  phone: string
  metro: string[]
  /**
   * Часы доступа на склад из UF_DOSTUP_TIME ("КРУГЛОСУТОЧНО").
   * Поле присутствует в API, но UI использует его лишь там, где значение
   * гарантированно читаемое. Помечено optional — не все склады заполняют поле.
   */
  access_hours?: string
  reception_hours: string
  price_per_sqm: number | null
  available_boxes_count: number
  total_boxes_count: number
  photo_url: string | null  // первое фото из галереи
  photos: string[]          // полная галерея (для detail-страницы)
  coords: { lat: number; lng: number } | null
  description: string | null
}

export interface ApiResponse<T> {
  data: T
  meta?: {
    total: number     // полное количество записей (без LIMIT/OFFSET)
    page: number
    per_page?: number
  }
}
