import Link from 'next/link'
import Image from 'next/image'
import { formatNumberRu } from '@/lib/format'
import type { Warehouse } from '@/types/warehouse'

const DISTRICT_COLORS: Record<string, { bg: string; text: string }> = {
  ЦАО:      { bg: '#E06820', text: '#fff' },
  САО:      { bg: '#2563EB', text: '#fff' },
  ЗАО:      { bg: '#16A34A', text: '#fff' },
  ЮАО:      { bg: '#7C3AED', text: '#fff' },
  ЮВАО:     { bg: '#DC2626', text: '#fff' },
  Балашиха: { bg: '#0891B2', text: '#fff' },
}

const BITRIX_CDN = 'https://alfasklad.ru'

function MetroIcon() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
      <circle cx="7" cy="7" r="6.5" stroke="currentColor" />
      <path d="M3 10 L7 4 L11 10" stroke="currentColor" strokeWidth="1.5" fill="none" />
      <path d="M5 10 L7 7 L9 10" stroke="currentColor" strokeWidth="1.5" fill="none" />
    </svg>
  )
}

function ClockIcon() {
  return (
    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
      <circle cx="6.5" cy="6.5" r="6" stroke="currentColor" />
      <path d="M6.5 3.5V7L8.5 9" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" />
    </svg>
  )
}

interface Props {
  warehouse: Warehouse
  index: number
}

export function WarehouseCard({ warehouse, index }: Props) {
  const district    = warehouse.district ? DISTRICT_COLORS[warehouse.district] : null
  const freeCount   = warehouse.available_boxes_count
  const totalCount  = warehouse.total_boxes_count
  const freePct     = totalCount > 0 ? Math.round((freeCount / totalCount) * 100) : 0

  const availColor =
    freePct > 40 ? '#16A34A' :
    freePct > 15 ? '#E06820' :
    '#DC2626'

  // photo_url — относительный путь /upload/..., добавляем CDN-хост
  const photoSrc = warehouse.photo_url
    ? `${BITRIX_CDN}${warehouse.photo_url}`
    : null

  // price_per_sqm теперь number, не string
  const priceFormatted = formatNumberRu(warehouse.price_per_sqm)

  return (
    <Link
      href={`/warehouses/${warehouse.slug}`}
      style={{ animationDelay: `${index * 60}ms` }}
      className="warehouse-card group"
    >
      <article>
        {/* Фото */}
        <div className="card-photo">
          {photoSrc ? (
            <Image
              src={photoSrc}
              alt={warehouse.name}
              fill
              sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
              className="card-photo-img"
            />
          ) : (
            <div className="card-photo-placeholder">
              <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                <rect x="4" y="10" width="32" height="24" rx="2" stroke="#C4C0B8" strokeWidth="1.5" />
                <path d="M4 18 L14 13 L22 19 L30 14 L36 18" stroke="#C4C0B8" strokeWidth="1.5" />
                <circle cx="28" cy="17" r="3" stroke="#C4C0B8" strokeWidth="1.5" />
              </svg>
            </div>
          )}

          {/* Бейдж района — показываем только если данные есть */}
          {district && warehouse.district && (
            <span
              className="district-badge"
              style={{ background: district.bg, color: district.text }}
            >
              {warehouse.district}
            </span>
          )}

          {/* Доступность поверх фото */}
          <div className="availability-overlay">
            <span style={{ color: availColor }} className="avail-count">
              {freeCount}
            </span>
            <span className="avail-label">свободных боксов</span>
          </div>
        </div>

        {/* Контент */}
        <div className="card-content">
          <h3 className="card-name">{warehouse.name}</h3>

          {warehouse.metro.length > 0 && (
            <div className="card-metro">
              <MetroIcon />
              {warehouse.metro.slice(0, 2).join(', ')}
            </div>
          )}

          <p className="card-address">{warehouse.address}</p>

          <div className="card-hours">
            <div className="hours-row">
              <ClockIcon />
              <span>Доступ <strong>24/7</strong></span>
            </div>
            <div className="hours-row">
              <ClockIcon />
              <span>Менеджер <strong>{warehouse.reception_hours}</strong></span>
            </div>
          </div>

          {/* Полоска доступности */}
          <div className="avail-bar-wrap">
            <div className="avail-bar-track">
              <div
                className="avail-bar-fill"
                style={{ width: `${freePct}%`, background: availColor }}
              />
            </div>
            <span className="avail-bar-label">
              {freePct}% боксов свободно
            </span>
          </div>

          {/* Цена + CTA */}
          <div className="card-footer">
            {priceFormatted && (
              <div className="card-price">
                <span className="price-from">от</span>
                <span className="price-value">{priceFormatted}</span>
                <span className="price-unit">₽/м²</span>
              </div>
            )}
            <span className="card-cta">
              Смотреть боксы
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                <path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </span>
          </div>
        </div>
      </article>
    </Link>
  )
}
