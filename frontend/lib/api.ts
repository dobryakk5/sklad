import 'server-only'

import type { Box, BoxApiParams } from '@/types/box'
import type { Warehouse, ApiResponse, WarehouseApiParams } from '@/types/warehouse'
import type { Review, SeoOverride, SeoPageType } from '@/types/admin'

function getApiBase(): string {
  const base =
    process.env.API_BASE_URL ??
    process.env.NEXT_PUBLIC_API_URL ??
    'http://127.0.0.1:8000/api'

  return base.replace(/\/+$/, '')
}

/**
 * TTL синхронизированы с Redis-кэшем на бэкенде (CacheKeys):
 *   склады (warehouses)  → TTL_AVAILABILITY = 60s   → revalidate: 60
 *   боксы   (boxes)      → TTL_BOXES         = 300s  → revalidate: 300
 *
 * Правило: revalidate фронта НЕ ДОЛЖЕН быть длиннее TTL бэкенда,
 * иначе Next.js ISR будет отдавать страницу с данными, которые
 * бэкенд уже обновил (особенно критично для available_boxes_count).
 */

export async function getWarehouses(params: WarehouseApiParams = {}): Promise<Warehouse[]> {
  const query = new URLSearchParams()
  if (params.rental_mode) query.set('rental_mode', params.rental_mode)

  const suffix = query.size > 0 ? `?${query}` : ''

  const res = await fetch(`${getApiBase()}/warehouses${suffix}`, {
    next: { revalidate: 60 },   // = CacheKeys::TTL_AVAILABILITY
  })
  if (!res.ok) throw new Error(`Не удалось получить список складов: ${res.status}`)
  const json: ApiResponse<Warehouse[]> = await res.json()
  return json.data
}

export async function getWarehouse(slug: string): Promise<Warehouse> {
  const res = await fetch(`${getApiBase()}/warehouses/${slug}`, {
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
  if (params.rental_mode)              query.set('rental_mode', params.rental_mode)
  if (params.page)                     query.set('page',        String(params.page))
  if (params.per_page)                 query.set('per_page',    String(params.per_page))

  const res = await fetch(`${getApiBase()}/boxes?${query}`, {
    next: { revalidate: 300 },  // = CacheKeys::TTL_BOXES
  })
  if (!res.ok) throw new Error(`Ошибка загрузки боксов: ${res.status}`)
  const json: ApiResponse<Box[]> = await res.json()
  return { data: json.data, total: json.meta?.total ?? json.data.length }
}

export async function getBox(id: string | number): Promise<Box> {
  const res = await fetch(`${getApiBase()}/boxes/${id}`, {
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

export async function getReviews(): Promise<Review[]> {
  try {
    const res = await fetch(`${getApiBase()}/reviews`, {
      next: { revalidate: 60 },
    })

    if (!res.ok) {
      return []
    }

    const json: ApiResponse<Review[]> = await res.json()
    return json.data
  } catch {
    return []
  }
}

export async function getSeoMeta(pageType: SeoPageType, pageSlug: string): Promise<SeoOverride | null> {
  try {
    const res = await fetch(`${getApiBase()}/seo/${pageType}/${pageSlug}`, {
      next: { revalidate: 60 },
    })

    if (!res.ok) {
      return null
    }

    const json: ApiResponse<SeoOverride> = await res.json()
    return json.data
  } catch {
    return null
  }
}
