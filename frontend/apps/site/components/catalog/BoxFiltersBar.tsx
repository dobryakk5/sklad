'use client'

import { useCallback } from 'react'
import type { BoxFilters, BoxFloor } from '@/types/box'

const STATUS_OPTIONS = [
  { value: 'all',       label: 'Все статусы' },
  { value: 'available', label: 'Только свободные' },
  { value: 'free',      label: 'Свободен сейчас' },
  { value: 'freeing_7', label: 'Освоб. через 7 дн.' },
]

const FLOOR_OPTIONS: { value: BoxFloor | 'all'; label: string }[] = [
  { value: 'all',    label: 'Любой этаж' },
  { value: 'first',  label: '1 этаж' },
  { value: 'second', label: '2 этаж' },
  { value: 'third',  label: '3 этаж' },
]

const SORT_OPTIONS = [
  { value: 'price_asc',   label: 'Цена ↑' },
  { value: 'price_desc',  label: 'Цена ↓' },
  { value: 'square_asc',  label: 'Площадь ↑' },
  { value: 'square_desc', label: 'Площадь ↓' },
]

// Диапазоны площадей для быстрого выбора
export const SIZE_RANGES = [
  { label: 'До 3 м²',   min: 0,  max: 3 },
  { label: '3–8 м²',    min: 3,  max: 8 },
  { label: '8–20 м²',   min: 8,  max: 20 },
  { label: 'Больше 20', min: 20, max: 999 },
]

interface Props {
  filters: BoxFilters
  totalCount: number
  filteredCount: number
  countLabel?: string
  availableSizeRanges: boolean[]
  onChange: (next: Partial<BoxFilters>) => void
  onReset: () => void
}

export function BoxFiltersBar({
  filters,
  totalCount,
  filteredCount,
  countLabel = 'помещений',
  availableSizeRanges,
  onChange,
  onReset,
}: Props) {
  const hasActiveFilters =
    (filters.status && filters.status !== 'available') ||
    filters.floor ||
    filters.square_min !== undefined ||
    filters.square_max !== undefined

  const handleSizeRange = useCallback(
    (min: number, max: number) => {
      const isActive = filters.square_min === min && filters.square_max === max
      onChange(isActive
        ? { square_min: undefined, square_max: undefined }
        : { square_min: min, square_max: max === 999 ? undefined : max }
      )
    },
    [filters.square_min, filters.square_max, onChange]
  )

  return (
    <div className="filters-bar">
      <div className="filters-row">
        {/* Статус */}
        <div className="filter-group">
          <label className="filter-label">Статус</label>
          <select
            className="filter-select"
            value={filters.status ?? 'available'}
            onChange={(e) => onChange({ status: e.target.value as BoxFilters['status'] })}
          >
            {STATUS_OPTIONS.map((o) => (
              <option key={o.value} value={o.value}>{o.label}</option>
            ))}
          </select>
        </div>

        {/* Этаж */}
        <div className="filter-group">
          <label className="filter-label">Этаж</label>
          <select
            className="filter-select"
            value={filters.floor ?? 'all'}
            onChange={(e) => {
              const v = e.target.value
              onChange({ floor: v === 'all' ? undefined : v as BoxFloor })
            }}
          >
            {FLOOR_OPTIONS.map((o) => (
              <option key={o.value} value={o.value}>{o.label}</option>
            ))}
          </select>
        </div>

        {/* Сортировка */}
        <div className="filter-group" style={{ marginLeft: 'auto' }}>
          <label className="filter-label">Сортировка</label>
          <select
            className="filter-select"
            value={filters.sort ?? 'price_asc'}
            onChange={(e) => onChange({ sort: e.target.value as BoxFilters['sort'] })}
          >
            {SORT_OPTIONS.map((o) => (
              <option key={o.value} value={o.value}>{o.label}</option>
            ))}
          </select>
        </div>
      </div>

      {/* Быстрый выбор площади */}
      <div className="filters-sizes">
        <span className="filter-label">Площадь:</span>
        <div className="size-chips">
          {SIZE_RANGES.map(({ label, min, max }, index) => {
            const isActive =
              filters.square_min === min &&
              (max === 999 ? filters.square_max === undefined : filters.square_max === max)
            const isAvailable = availableSizeRanges[index] ?? false
            return (
              <button
                key={label}
                type="button"
                disabled={!isAvailable}
                className={`size-chip${isActive ? ' active' : ''}${!isAvailable ? ' size-chip--disabled' : ''}`}
                onClick={() => handleSizeRange(min, max)}
              >
                {label}
              </button>
            )
          })}
        </div>

        {/* Счётчик + сброс */}
        <div className="filters-meta">
          <span className="filters-count">
            {filteredCount} из {totalCount} {countLabel}
          </span>
          {hasActiveFilters && (
            <button className="filters-reset" onClick={onReset}>
              Сбросить фильтры
            </button>
          )}
        </div>
      </div>
    </div>
  )
}
