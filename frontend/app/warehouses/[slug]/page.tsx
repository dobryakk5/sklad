import { Suspense } from 'react'
import Link from 'next/link'
import Image from 'next/image'
import { getBoxes, getSeoMeta, getWarehouse } from '@/lib/api'
import { formatNumberRu } from '@/lib/format'
import { BITRIX_BASE } from '@/lib/constants'
import { getRentalCatalogPath, getRentalModeConfig, normalizeRentalMode } from '@/lib/rentalModes'
import { BoxGrid } from '@/components/catalog/BoxGrid'
import type { Metadata } from 'next'
import type { SeoOverride } from '@/types/admin'
import type { RentalMode } from '@/types/rental'
import type { Warehouse } from '@/types/warehouse'

interface Props {
  params: Promise<{ slug: string }>
  searchParams: Promise<{ mode?: string }>
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
  const { slug } = await params
  const { mode: rawMode } = await searchParams
  const mode = normalizeRentalMode(rawMode)
  const config = getRentalModeConfig(mode)
  try {
    const [warehouse, seo] = await Promise.all([
      getWarehouse(slug),
      getSeoMeta('warehouse', slug),
    ])
    const pricePerSqm = formatNumberRu(warehouse.price_per_sqm)
    const base: Metadata = {
      title: `${config.pluralLabel} на складе ${warehouse.name}`,
      description: pricePerSqm
        ? `${config.label} от ${pricePerSqm} ₽/м² на складе ${warehouse.name}. ${warehouse.address}. Доступ 24/7.`
        : `${config.label} на складе ${warehouse.name}. ${warehouse.address}. Доступ 24/7.`,
    }

    return applySeoMetadata(base, seo)
  } catch {
    return { title: 'Каталог склада' }
  }
}

function GridSkeleton() {
  return (
    <div className="box-grid">
      {Array.from({ length: 9 }).map((_, i) => (
        <div key={i} className="box-card-skeleton" />
      ))}
    </div>
  )
}

async function CatalogContent({ warehouse, mode }: { warehouse: Warehouse; mode: RentalMode }) {
  const { data: boxes } = await getBoxes({ stock_id: warehouse.id, rental_mode: mode })
  return <BoxGrid boxes={boxes} warehouse={warehouse} mode={mode} />
}

export default async function WarehouseCatalogPage({ params, searchParams }: Props) {
  const { slug } = await params
  const { mode: rawMode } = await searchParams
  const mode = normalizeRentalMode(rawMode)
  const modeConfig = getRentalModeConfig(mode)
  const warehouse = await getWarehouse(slug)
  const heroPhoto = warehouse.photo_url ?? warehouse.photos[0] ?? null

  return (
    <main className="catalog-main">
      <div className="container">

        <nav className="breadcrumbs" aria-label="Навигация">
          <Link href="/" className="bc-link">Главная</Link>
          <span className="bc-sep">›</span>
          <Link href={getRentalCatalogPath(mode)} className="bc-link">{modeConfig.label}</Link>
          <span className="bc-sep">›</span>
          <span className="bc-current">{warehouse.name}</span>
        </nav>

        <div className="catalog-header">
          <div>
            <h1 className="catalog-title">{modeConfig.pluralLabel} на складе {warehouse.name}</h1>
            <p className="catalog-subtitle">{warehouse.address}</p>
          </div>
          <Link href={getRentalCatalogPath(mode)} className="btn-primary">
            Выбрать другой склад
          </Link>
        </div>

        {heroPhoto && (
          <div className="catalog-hero">
            <Image
              src={`${BITRIX_BASE}${heroPhoto}`}
              alt={warehouse.name}
              fill
              priority
              sizes="(max-width: 640px) 100vw, 1200px"
              className="catalog-hero-img"
            />
          </div>
        )}

        <Suspense fallback={<GridSkeleton />}>
          <CatalogContent warehouse={warehouse} mode={mode} />
        </Suspense>

      </div>
    </main>
  )
}
