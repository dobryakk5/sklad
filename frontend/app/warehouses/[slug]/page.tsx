import { Suspense } from 'react'
import Link from 'next/link'
import Image from 'next/image'
import { getWarehouse, getBoxes } from '@/lib/api'
import { formatNumberRu } from '@/lib/format'
import { BITRIX_BASE } from '@/lib/constants'
import { BoxGrid } from '@/components/catalog/BoxGrid'
import type { Metadata } from 'next'
import type { Warehouse } from '@/types/warehouse'

interface Props {
  params: Promise<{ slug: string }>
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params
  try {
    const warehouse = await getWarehouse(slug)
    const pricePerSqm = formatNumberRu(warehouse.price_per_sqm)
    return {
      title: `Боксы на складе ${warehouse.name}`,
      description: pricePerSqm
        ? `Аренда боксов от ${pricePerSqm} ₽/м² на складе ${warehouse.name}. ${warehouse.address}. Доступ 24/7.`
        : `Аренда боксов на складе ${warehouse.name}. ${warehouse.address}. Доступ 24/7.`,
    }
  } catch {
    return { title: 'Каталог боксов' }
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

async function CatalogContent({ warehouse }: { warehouse: Warehouse }) {
  const { data: boxes } = await getBoxes({ stock_id: warehouse.id })
  return <BoxGrid boxes={boxes} warehouse={warehouse} />
}

export default async function WarehouseCatalogPage({ params }: Props) {
  const { slug } = await params
  const warehouse = await getWarehouse(slug)
  const heroPhoto = warehouse.photo_url ?? warehouse.photos[0] ?? null

  return (
    <main className="catalog-main">
      <div className="container">

        <nav className="breadcrumbs" aria-label="Навигация">
          <Link href="/" className="bc-link">Главная</Link>
          <span className="bc-sep">›</span>
          <span className="bc-current">{warehouse.name}</span>
        </nav>

        <div className="catalog-header">
          <div>
            <h1 className="catalog-title">Склад {warehouse.name}</h1>
            <p className="catalog-subtitle">{warehouse.address}</p>
          </div>
          <a
            href="https://alfasklad.ru/rental_catalog/"
            target="_blank"
            rel="noopener noreferrer"
            className="btn-primary"
          >
            Арендовать на alfasklad.ru ↗
          </a>
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
          <CatalogContent warehouse={warehouse} />
        </Suspense>

      </div>
    </main>
  )
}
