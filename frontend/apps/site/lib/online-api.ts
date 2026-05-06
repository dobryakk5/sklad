// lib/online-api.ts
// Клиентский фетчер для /online — не использует 'server-only',
// поэтому можно вызывать из 'use client' компонентов.
// Использует NEXT_PUBLIC_API_URL (тот же бэкенд, что и server-side api.ts).

import type { Box } from '@/types/box'
import type { ApiResponse } from '@/types/warehouse'

/**
 * Загружает доступные боксы для выбранного склада.
 * Вызывается клиентски после того, как пользователь выбрал склад.
 */
export async function fetchBoxesByWarehouse(stockId: number): Promise<Box[]> {
  const params = new URLSearchParams({
    stock_id: String(stockId),
    per_page: '100',
  })

  const res = await fetch(`/api/online/boxes?${params}`, {
    cache: 'no-store',
  })

  if (!res.ok) {
    throw new Error(`Ошибка загрузки боксов: ${res.status}`)
  }

  const json: ApiResponse<Box[]> = await res.json()

  // Отдаём только свободные и освобождающиеся
  return json.data.filter(b =>
    b.status === 'free' ||
    b.status === 'freeing_7' ||
    b.status === 'freeing_14'
  )
}
