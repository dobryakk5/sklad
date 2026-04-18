'use client'

import { useState, useMemo } from 'react'
import type { Box, BoxFilters } from '@/types/box'
import type { RentalMode } from '@/types/rental'
import type { Warehouse } from '@/types/warehouse'
import { formatNumberRu } from '@/lib/format'
import { getCatalogModeCopy } from '@/lib/rentalModes'
import { BoxCard } from './BoxCard'
import { BoxFiltersBar, SIZE_RANGES } from './BoxFiltersBar'

const DEFAULT_FILTERS: BoxFilters = {
  status: 'available',
  sort: 'price_asc',
}

const AVAILABLE_STATUSES = new Set(['free', 'freeing_7', 'freeing_14'])

function sortableNumber(value: number | null | undefined, emptyRank: number): number {
  return value ?? emptyRank
}

function sortBoxes(boxes: Box[], sort: BoxFilters['sort']): Box[] {
  return [...boxes].sort((a, b) => {
    switch (sort) {
      case 'price_asc':
        return sortableNumber(a.price, Number.POSITIVE_INFINITY) - sortableNumber(b.price, Number.POSITIVE_INFINITY)
      case 'price_desc':
        return sortableNumber(b.price, Number.NEGATIVE_INFINITY) - sortableNumber(a.price, Number.NEGATIVE_INFINITY)
      case 'square_asc':
        return sortableNumber(a.square, Number.POSITIVE_INFINITY) - sortableNumber(b.square, Number.POSITIVE_INFINITY)
      case 'square_desc':
        return sortableNumber(b.square, Number.NEGATIVE_INFINITY) - sortableNumber(a.square, Number.NEGATIVE_INFINITY)
      default:
        return sortableNumber(a.price, Number.POSITIVE_INFINITY) - sortableNumber(b.price, Number.POSITIVE_INFINITY)
    }
  })
}

interface Props {
  boxes: Box[]
  warehouse: Warehouse
  mode?: RentalMode
}

export function BoxGrid({ boxes, warehouse, mode }: Props) {
  const [filters, setFilters] = useState<BoxFilters>(DEFAULT_FILTERS)
  const modeCopy = getCatalogModeCopy(mode)

  const handleChange = (next: Partial<BoxFilters>) => {
    setFilters((prev) => ({ ...prev, ...next }))
  }

  const handleReset = () => setFilters(DEFAULT_FILTERS)

  const boxesForSizeAvailability = useMemo(() => {
    let result = boxes

    if (filters.status && filters.status !== 'all') {
      if (filters.status === 'available') {
        result = result.filter((b) => AVAILABLE_STATUSES.has(b.status))
      } else {
        result = result.filter((b) => b.status === filters.status)
      }
    }

    if (filters.floor) {
      result = result.filter((b) => b.floor === filters.floor)
    }

    return result
  }, [boxes, filters.status, filters.floor])

  const availableSizeRanges = useMemo(
    () => SIZE_RANGES.map(({ min, max }) => (
      boxesForSizeAvailability.some((box) => {
        if (box.square == null) {
          return false
        }

        return box.square >= min && box.square <= max
      })
    )),
    [boxesForSizeAvailability],
  )

  const filtered = useMemo(() => {
    let result = boxes

    // Фильтр статуса
    if (filters.status && filters.status !== 'all') {
      if (filters.status === 'available') {
        result = result.filter((b) => AVAILABLE_STATUSES.has(b.status))
      } else {
        result = result.filter((b) => b.status === filters.status)
      }
    }

    // Фильтр этажа
    if (filters.floor) {
      result = result.filter((b) => b.floor === filters.floor)
    }

    // Фильтр площади
    if (filters.square_min !== undefined) {
      result = result.filter((b) => b.square != null && b.square >= filters.square_min!)
    }
    if (filters.square_max !== undefined) {
      result = result.filter((b) => b.square != null && b.square <= filters.square_max!)
    }

    return sortBoxes(result, filters.sort)
  }, [boxes, filters])

  const availableCount = boxes.filter((b) => AVAILABLE_STATUSES.has(b.status)).length
  const warehousePrice = formatNumberRu(warehouse.price_per_sqm)

  return (
    <div className="catalog-body">
      {/* Шапка с инфо о складе */}
      <div className="warehouse-info-bar">
        <div className="winfo-main">
          <div className="winfo-badge">
            {availableCount > 0
              ? <><span className="winfo-dot winfo-dot--green" />Есть свободные {modeCopy.pluralLabel.toLowerCase()}</>
              : <><span className="winfo-dot winfo-dot--red" />Все {modeCopy.pluralLabel.toLowerCase()} заняты</>
            }
          </div>
          <div className="winfo-row">
            <span className="winfo-item">
              <MetroIcon />
              {warehouse.metro.slice(0, 2).join(', ')}
            </span>
            <span className="winfo-sep">·</span>
            <span className="winfo-item">{warehouse.address}</span>
            <span className="winfo-sep">·</span>
            <span className="winfo-item">Менеджер: {warehouse.reception_hours}</span>
          </div>
        </div>
        <div className="winfo-price">
          <span className="winfo-from">от</span>
          <span className="winfo-pval">{warehousePrice ?? '—'}</span>
          <span className="winfo-punit">₽/м²</span>
        </div>
      </div>

      {/* Фильтры */}
      <BoxFiltersBar
        filters={filters}
        totalCount={boxes.length}
        filteredCount={filtered.length}
        countLabel={modeCopy.listingLabel}
        availableSizeRanges={availableSizeRanges}
        onChange={handleChange}
        onReset={handleReset}
      />

      {/* Сетка помещений */}
      {filtered.length > 0 ? (
        <div className="box-grid">
          {filtered.map((box, i) => (
            <BoxCard
              key={box.id}
              box={box}
              warehouseSlug={warehouse.slug}
              mode={mode}
              index={i}
            />
          ))}
        </div>
      ) : (
        <div className="box-empty">
          <div className="box-empty-icon">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
              <rect x="6" y="14" width="36" height="28" rx="3" stroke="#C4C0B8" strokeWidth="1.5" />
              <path d="M6 22 L24 30 L42 22" stroke="#C4C0B8" strokeWidth="1.5" />
              <path d="M24 30 L24 42" stroke="#C4C0B8" strokeWidth="1.5" />
              <path d="M16 6 L32 6 L42 14" stroke="#C4C0B8" strokeWidth="1.5" />
            </svg>
          </div>
          <p className="box-empty-title">Сейчас нет подходящих {modeCopy.listingLabel}</p>
          <p className="box-empty-sub">Попробуйте изменить фильтры или выбрать другой склад</p>
          <button className="box-empty-reset" onClick={handleReset}>
            Сбросить фильтры
          </button>
        </div>
      )}
    </div>
  )
}

function MetroIcon() {
  return (
    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true" style={{ flexShrink: 0 }}>
      <circle cx="6.5" cy="6.5" r="6" stroke="currentColor" />
      <path d="M3 10 L6.5 4.5 L10 10" stroke="currentColor" strokeWidth="1.3" fill="none" />
      <path d="M4.5 10 L6.5 7.5 L8.5 10" stroke="currentColor" strokeWidth="1.3" fill="none" />
    </svg>
  )
}
