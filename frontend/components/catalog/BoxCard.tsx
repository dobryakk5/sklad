import Link from 'next/link'
import type { Box } from '@/types/box'

import { formatNumberRu } from '@/lib/format'
import { BITRIX_BASE, RENTAL_CATALOG_URL } from '@/lib/constants'

const STATUS_LABEL: Record<Box['status'], string> = {
  free:        'Свободен',
  freeing_7:   'Освоб. через 7 дн.',
  freeing_14:  'Освоб. через 14 дн.',
  reserved:    'Зарезервирован',
  rented:      'Занят',
}

const STATUS_COLOR: Record<Box['status'], { bg: string; text: string }> = {
  free:        { bg: '#ECFDF5', text: '#065F46' },
  freeing_7:   { bg: '#FEF3C7', text: '#92400E' },
  freeing_14:  { bg: '#FEF9C3', text: '#713F12' },
  reserved:    { bg: '#F0FDF4', text: '#166534' },
  rented:      { bg: '#FEF2F2', text: '#991B1B' },
}

const FLOOR_LABEL: Record<Box['floor'], string> = {
  first:  '1 этаж',
  second: '2 этаж',
  third:  '3 этаж',
  other:  'Другой этаж',
}

const FLOOR_PREF: Record<Box['floor'], 0 | 1 | 2> = {
  first: 2, second: 1, third: 1, other: 0,
}

function FloorBadge({ floor }: { floor: Box['floor'] }) {
  const pref = FLOOR_PREF[floor]
  return (
    <span
      className="floor-badge"
      style={{
        background: pref === 2 ? '#EEF2FF' : pref === 1 ? '#F5F5F5' : '#FAFAFA',
        color: pref === 2 ? '#3730A3' : '#6B7280',
      }}
    >
      {FLOOR_LABEL[floor]}
      {pref === 2 && ' ★'}
    </span>
  )
}

function BoxIcon() {
  return (
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true">
      <rect x="4" y="10" width="24" height="18" rx="2" stroke="#C4C0B8" strokeWidth="1.5" />
      <path d="M4 14 L16 20 L28 14" stroke="#C4C0B8" strokeWidth="1.5" />
      <path d="M16 20 L16 28" stroke="#C4C0B8" strokeWidth="1.5" />
      <path d="M10 4 L22 4 L28 10" stroke="#C4C0B8" strokeWidth="1.5" />
    </svg>
  )
}

interface Props {
  box: Box
  warehouseSlug: string
  index: number
}

export function BoxCard({ box, warehouseSlug, index }: Props) {
  const isAvailable = box.status === 'free'
  const isSoon = box.status === 'freeing_7' || box.status === 'freeing_14'
  const isBlocked = box.status === 'rented' || box.status === 'reserved'
  const statusStyle = STATUS_COLOR[box.status]
  const monthlyPrice = formatNumberRu(box.price)
  const pricePerSqm = formatNumberRu(box.price_per_sqm)

  const rentUrl = `${RENTAL_CATALOG_URL}?box=${box.code_1c}`

  return (
    <article
      className={`box-card${isBlocked ? ' box-card--blocked' : ''}`}
      style={{ animationDelay: `${index * 40}ms` }}
    >
      {/* Верхняя часть: иконка + номер + статус */}
      <div className="box-card-top">
        <div className="box-icon-wrap">
          <BoxIcon />
          <span className="box-number">#{box.box_number}</span>
        </div>
        <span
          className="box-status"
          style={{ background: statusStyle.bg, color: statusStyle.text }}
        >
          {STATUS_LABEL[box.status]}
        </span>
      </div>

      {/* Характеристики */}
      <div className="box-specs">
        <div className="box-spec-main">
          <span className="spec-value">{box.square}</span>
          <span className="spec-unit">м²</span>
        </div>
        <div className="box-spec-list">
          <div className="spec-row">
            <span className="spec-label">Объём</span>
            <span className="spec-val">{box.volume} м³</span>
          </div>
          <div className="spec-row">
            <span className="spec-label">Этаж</span>
            <FloorBadge floor={box.floor} />
          </div>
          <div className="spec-row">
            <span className="spec-label">Тип</span>
            <span className="spec-val">{box.object_type}</span>
          </div>
        </div>
      </div>

      {/* Цена */}
      <div className="box-price-row">
        <div>
          <div className="box-price">
            {monthlyPrice ?? '—'} <span>₽/мес</span>
          </div>
          <div className="box-price-m2">
            {pricePerSqm ?? '—'} ₽/м²
          </div>
        </div>
      </div>

      {/* Кнопки */}
      <div className="box-actions">
        <Link
          href={`/warehouses/${warehouseSlug}/boxes/${box.id}`}
          className="box-btn-detail"
        >
          Подробнее
        </Link>

        {isAvailable && (
          <a
            href={rentUrl}
            target="_blank"
            rel="noopener noreferrer"
            className="box-btn-rent"
          >
            Арендовать
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
              <path d="M2 2h8v8M10 2 L2 10" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
            </svg>
          </a>
        )}

        {isSoon && (
          <a
            href={`${BITRIX_BASE}/cabinet/`}
            target="_blank"
            rel="noopener noreferrer"
            className="box-btn-soon"
          >
            Записаться
          </a>
        )}

        {isBlocked && (
          <span className="box-btn-disabled">Недоступен</span>
        )}
      </div>
    </article>
  )
}
