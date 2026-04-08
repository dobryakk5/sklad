import Link from 'next/link'
import { notFound } from 'next/navigation'
import { getBox, getSeoMeta, getWarehouse } from '@/lib/api'
import { formatNumberRu } from '@/lib/format'
import {
  getRentalCatalogPath,
  getRentalModeConfig,
  inferRentalModeFromBox,
  normalizeRentalMode,
} from '@/lib/rentalModes'
import { BoxGallery } from '@/components/box/BoxGallery'
import type { Metadata } from 'next'
import type { SeoOverride } from '@/types/admin'
import type { Box } from '@/types/box'
import type { RentalMode } from '@/types/rental'

const BITRIX_BASE = 'https://alfasklad.ru'

interface Props {
  params: Promise<{ slug: string; id: string }>
  searchParams: Promise<{ mode?: string }>
}

function resolveMode(rawMode: string | undefined, box: Box): RentalMode {
  return rawMode ? normalizeRentalMode(rawMode) : inferRentalModeFromBox(box)
}

function displayTitle(box: Box, label: string): string {
  return box.box_number ? `${label} №${box.box_number}` : (box.name || label)
}

function applySeoMetadata(base: Metadata, seo: SeoOverride | null): Metadata {
  if (!seo) {
    return base
  }

  return {
    ...base,
    title: seo.title ?? base.title,
    description: seo.description ?? base.description,
    alternates: seo.canonical
      ? { ...base.alternates, canonical: seo.canonical }
      : base.alternates,
    openGraph: {
      ...base.openGraph,
      title: seo.og_title ?? seo.title ?? undefined,
      description: seo.og_description ?? seo.description ?? undefined,
      images: seo.og_image ? [seo.og_image] : undefined,
    },
  }
}

export async function generateMetadata({ params, searchParams }: Props): Promise<Metadata> {
  const { id, slug } = await params
  try {
    const [box, warehouse, seo] = await Promise.all([
      getBox(id),
      getWarehouse(slug),
      getSeoMeta('box', id),
    ])
    const { mode: rawMode } = await searchParams
    const mode = resolveMode(rawMode, box)
    const config = getRentalModeConfig(mode)
    const title = displayTitle(box, config.itemLabel)
    const monthlyPrice = formatNumberRu(box.price)
    const base: Metadata = {
      title: `${title} · ${box.square} м² · ${warehouse.name}`,
      description: monthlyPrice
        ? `${config.label}: ${title.toLowerCase()}, ${box.square} м², ${monthlyPrice} ₽/мес. Склад ${warehouse.name}, ${warehouse.address}.`
        : `${config.label}: ${title.toLowerCase()} на складе ${warehouse.name}, ${warehouse.address}.`,
    }

    return applySeoMetadata(base, seo)
  } catch {
    return { title: 'Объект каталога' }
  }
}

// ─── Статусы ─────────────────────────────────────────────────────
const STATUS_META: Record<Box['status'], { label: string; bg: string; text: string; dot: string }> = {
  free:        { label: 'Свободен',            bg: '#ECFDF5', text: '#065F46', dot: '#16A34A' },
  freeing_7:   { label: 'Освободится через 7 дней',  bg: '#FEF3C7', text: '#92400E', dot: '#D97706' },
  freeing_14:  { label: 'Освободится через 14 дней', bg: '#FEF9C3', text: '#713F12', dot: '#CA8A04' },
  reserved:    { label: 'Зарезервирован',      bg: '#F0FDF4', text: '#166534', dot: '#22C55E' },
  rented:      { label: 'Занят',               bg: '#FEF2F2', text: '#991B1B', dot: '#DC2626' },
}

const FLOOR_LABEL: Record<Box['floor'], string> = {
  first: '1-й этаж',
  second: '2-й этаж',
  third: '3-й этаж',
  other: 'Другой',
}

// ─── Иконки ──────────────────────────────────────────────────────
function IconMetro() {
  return (
    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
      <circle cx="7.5" cy="7.5" r="7" stroke="currentColor" />
      <path d="M3.5 11.5 L7.5 5 L11.5 11.5" stroke="currentColor" strokeWidth="1.4" fill="none" />
      <path d="M5.5 11.5 L7.5 8.5 L9.5 11.5" stroke="currentColor" strokeWidth="1.4" fill="none" />
    </svg>
  )
}
function IconClock() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
      <circle cx="7" cy="7" r="6.5" stroke="currentColor" />
      <path d="M7 4V7.5L9.5 10" stroke="currentColor" strokeWidth="1.3" strokeLinecap="round" />
    </svg>
  )
}
function IconArrowOut() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
      <path d="M2 2h10v10M12 2 L2 12" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
    </svg>
  )
}
function IconPhone() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
      <path d="M2 2.5C2 2.5 3 1 4.5 1C5.5 1 6 2 6.5 3L7.5 5C7.5 5 8 6 7 7L6 8C6 8 7 10 8 11C9 12 11 13 11 13L12 12C13 11 14 11.5 14 11.5L12.5 10C11.5 9.5 11 9.5 10.5 10L10 10.5C9.5 11 8 9.5 7 8.5L5.5 7C4.5 6 3 4.5 3.5 4L4 3.5C4.5 3 4.5 2.5 4 1.5L2 2.5Z" stroke="currentColor" strokeWidth="1.2" />
    </svg>
  )
}

// ─── Страница ─────────────────────────────────────────────────────
export default async function BoxDetailPage({ params, searchParams }: Props) {
  const { slug, id } = await params

  const [box, warehouse] = await Promise.all([
    getBox(id),
    getWarehouse(slug),
  ])

  // Проверяем что объект реально принадлежит этому складу
  if (box.warehouse_id !== warehouse.id) notFound()

  const { mode: rawMode } = await searchParams
  const mode = resolveMode(rawMode, box)
  const modeConfig = getRentalModeConfig(mode)
  const objectTitle = displayTitle(box, modeConfig.itemLabel)
  const warehouseHref = `/warehouses/${slug}?mode=${mode}`

  const status = STATUS_META[box.status]
  const isAvailable = box.status === 'free'
  const isSoon = box.status === 'freeing_7' || box.status === 'freeing_14'
  const rentUrl = `${BITRIX_BASE}/rental_catalog/?box=${box.code_1c}`
  const monthlyPrice = formatNumberRu(box.price)
  const pricePerSqm = formatNumberRu(box.price_per_sqm)
  const warehousePricePerSqm = formatNumberRu(warehouse.price_per_sqm)

  return (
    <main className="box-detail-main">
      <div className="container">

        {/* Хлебные крошки */}
        <nav className="breadcrumbs" aria-label="Навигация">
          <Link href="/" className="bc-link">Главная</Link>
          <span className="bc-sep">›</span>
          <Link href={getRentalCatalogPath(mode)} className="bc-link">{modeConfig.label}</Link>
          <span className="bc-sep">›</span>
          <Link href={warehouseHref} className="bc-link">{warehouse.name}</Link>
          <span className="bc-sep">›</span>
          <span className="bc-current">{objectTitle}</span>
        </nav>

        {/* Основной грид: фото слева, инфо справа */}
        <div className="box-detail-grid">

          {/* Левая колонка — галерея */}
          <div className="box-detail-left">
            <BoxGallery photoUrl={box.photo_url} title={objectTitle} />
          </div>

          {/* Правая колонка — вся информация */}
          <div className="box-detail-right">

            {/* Заголовок + статус */}
            <div className="bd-header">
              <div>
                <p className="bd-eyebrow">{modeConfig.itemLabel} для хранения · {warehouse.name}</p>
                <h1 className="bd-title">{objectTitle}</h1>
              </div>
              <span
                className="bd-status"
                style={{ background: status.bg, color: status.text }}
              >
                <span
                  className="bd-status-dot"
                  style={{ background: status.dot }}
                />
                {status.label}
              </span>
            </div>

            {/* Ключевые характеристики */}
            <div className="bd-specs">
              <div className="bd-spec-hero">
                <span className="bd-spec-val">{box.square}</span>
                <span className="bd-spec-unit">м²</span>
              </div>
              <div className="bd-spec-grid">
                <div className="bd-spec-item">
                  <span className="bd-spec-label">Объём</span>
                  <span className="bd-spec-data">{box.volume} м³</span>
                </div>
                <div className="bd-spec-item">
                  <span className="bd-spec-label">Этаж</span>
                  <span className="bd-spec-data">{FLOOR_LABEL[box.floor]}</span>
                </div>
                <div className="bd-spec-item">
                  <span className="bd-spec-label">Тип</span>
                  <span className="bd-spec-data">{box.object_type}</span>
                </div>
                <div className="bd-spec-item">
                  <span className="bd-spec-label">Аренда</span>
                  <span className="bd-spec-data">{box.rent_type}</span>
                </div>
              </div>
            </div>

            {/* Цена */}
            <div className="bd-price-block">
              <div className="bd-price-main">
                <span className="bd-price-val">
                  {monthlyPrice ?? '—'}
                </span>
                <span className="bd-price-suffix">₽/мес</span>
              </div>
              <div className="bd-price-breakdown">
                {pricePerSqm ?? '—'} ₽/м²
                &nbsp;×&nbsp;
                {box.square ?? '—'} м²
              </div>
              <p className="bd-price-note">
                Минимальный срок — 1 месяц. Оплата в конце каждого месяца на следующий.
              </p>
            </div>

            {/* CTA кнопки */}
            <div className="bd-actions">
              {isAvailable && (
                <a
                  href={rentUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="bd-btn-rent"
                >
                  Арендовать на alfasklad.ru
                  <IconArrowOut />
                </a>
              )}
              {isSoon && (
                <>
                  <a
                    href={`${BITRIX_BASE}/cabinet/`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="bd-btn-rent"
                  >
                    Записаться в очередь
                    <IconArrowOut />
                  </a>
                  <p className="bd-soon-note">
                    Помещение освободится — мы свяжемся с вами первыми
                  </p>
                </>
              )}
              {!isAvailable && !isSoon && (
                <div className="bd-btn-unavail">
                  Сейчас занято
                </div>
              )}
              <a href="tel:+74952663974" className="bd-btn-call">
                <IconPhone />
                Позвонить менеджеру
              </a>
            </div>

            {/* Что входит в стоимость */}
            <div className="bd-included">
              <p className="bd-included-title">В стоимость аренды входит</p>
              <ul className="bd-included-list">
                {[
                  'Парковка на территории',
                  'Тележки, рохли, штабелеры',
                  'Коммунальные платежи и Wi-Fi',
                  'Компенсация при повреждении имущества',
                ].map((item) => (
                  <li key={item} className="bd-included-item">
                    <span className="bd-check" aria-hidden="true" />
                    {item}
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </div>

        {/* Блок склада */}
        <section className="bd-warehouse-section" aria-label="Информация о складе">
          <h2 className="bd-warehouse-title">Склад {warehouse.name}</h2>
          <div className="bd-warehouse-grid">

            <div className="bd-wcard">
              <p className="bd-wcard-label">Адрес</p>
              <p className="bd-wcard-val">{warehouse.address}</p>
            </div>

            {warehouse.metro.length > 0 && (
              <div className="bd-wcard">
                <p className="bd-wcard-label">Метро</p>
                <div className="bd-metro-list">
                  {warehouse.metro.map((m) => (
                    <span key={m} className="bd-metro-item">
                      <IconMetro />
                      {m}
                    </span>
                  ))}
                </div>
              </div>
            )}

            <div className="bd-wcard">
              <p className="bd-wcard-label">Доступ на склад</p>
              <p className="bd-wcard-val bd-wcard-val--accent">
                <IconClock /> 24/7 по пин-коду
              </p>
            </div>

            <div className="bd-wcard">
              <p className="bd-wcard-label">Менеджер на ресепшн</p>
              <p className="bd-wcard-val">{warehouse.reception_hours}</p>
            </div>

            <div className="bd-wcard">
              <p className="bd-wcard-label">Цена на складе</p>
              <p className="bd-wcard-val">
                от {warehousePricePerSqm ?? '—'} ₽/м²
              </p>
            </div>

            <div className="bd-wcard bd-wcard--action">
              <Link href={warehouseHref} className="bd-wcard-link">
                Все помещения этого склада →
              </Link>
            </div>
          </div>
        </section>

      </div>
    </main>
  )
}
