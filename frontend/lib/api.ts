import type { Box, BoxApiParams } from '@/types/box'
import type { Warehouse, ApiResponse } from '@/types/warehouse'

const BASE = process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000/api'

/**
 * TTL синхронизированы с Redis-кэшем на бэкенде (CacheKeys):
 *   склады (warehouses)  → TTL_AVAILABILITY = 60s   → revalidate: 60
 *   боксы   (boxes)      → TTL_BOXES         = 300s  → revalidate: 300
 *
 * Правило: revalidate фронта НЕ ДОЛЖЕН быть длиннее TTL бэкенда,
 * иначе Next.js ISR будет отдавать страницу с данными, которые
 * бэкенд уже обновил (особенно критично для available_boxes_count).
 */

export async function getWarehouses(): Promise<Warehouse[]> {
  const res = await fetch(`${BASE}/warehouses`, {
    next: { revalidate: 60 },   // = CacheKeys::TTL_AVAILABILITY
  })
  if (!res.ok) throw new Error(`Не удалось получить список складов: ${res.status}`)
  const json: ApiResponse<Warehouse[]> = await res.json()
  return json.data
}

export async function getWarehouse(slug: string): Promise<Warehouse> {
  const res = await fetch(`${BASE}/warehouses/${slug}`, {
    next: { revalidate: 60 },   // = CacheKeys::TTL_AVAILABILITY
  })
  if (res.status === 404) {
    const { notFound } = await import('next/navigation')
    notFound()
  }
  if (!res.ok) throw new Error(`Склад не найден: ${res.status}`)
  const json: ApiResponse<Warehouse> = await res.json()
  return json.data
}

export async function getBoxes(params: BoxApiParams): Promise<{ data: Box[]; total: number }> {
  const query = new URLSearchParams()

  if (params.stock_id)                 query.set('stock_id',    String(params.stock_id))
  if (params.status)                   query.set('status',      params.status)
  if (params.size_min !== undefined)   query.set('size_min',    String(params.size_min))
  if (params.size_max !== undefined)   query.set('size_max',    String(params.size_max))
  if (params.object_type)              query.set('object_type', params.object_type)
  if (params.page)                     query.set('page',        String(params.page))
  if (params.per_page)                 query.set('per_page',    String(params.per_page))

  const res = await fetch(`${BASE}/boxes?${query}`, {
    next: { revalidate: 300 },  // = CacheKeys::TTL_BOXES
  })
  if (!res.ok) throw new Error(`Ошибка загрузки боксов: ${res.status}`)
  const json: ApiResponse<Box[]> = await res.json()
  return { data: json.data, total: json.meta?.total ?? json.data.length }
}

export async function getBox(id: string | number): Promise<Box> {
  const res = await fetch(`${BASE}/boxes/${id}`, {
    next: { revalidate: 300 },  // = CacheKeys::TTL_BOXES
  })
  if (res.status === 404) {
    const { notFound } = await import('next/navigation')
    notFound()
  }
  if (!res.ok) throw new Error(`Ошибка загрузки бокса: ${res.status}`)
  const json: ApiResponse<Box> = await res.json()
  return json.data
}
