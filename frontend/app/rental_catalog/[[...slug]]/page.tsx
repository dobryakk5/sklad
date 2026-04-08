import { Suspense } from 'react'
import { notFound } from 'next/navigation'
import { getWarehouses } from '@/lib/api'
import { getRentalModeConfig, rentalModeFromCatalogSlug } from '@/lib/rentalModes'
import { WarehouseGrid } from '@/components/home/WarehouseGrid'
import type { Metadata } from 'next'
import type { RentalMode } from '@/types/rental'

interface Props {
  params: Promise<{ slug?: string[] }>
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params
  const mode = rentalModeFromCatalogSlug(slug)

  if (mode === null) {
    return { title: 'Аренда' }
  }

  const config = getRentalModeConfig(mode)

  return {
    title: config.label,
    description: config.heroDescription,
  }
}

function GridSkeleton() {
  return (
    <div className="warehouse-grid">
      {Array.from({ length: 6 }).map((_, i) => (
        <div key={i} className="card-skeleton" />
      ))}
    </div>
  )
}

async function WarehousesAsync({ mode }: { mode: RentalMode }) {
  const warehouses = await getWarehouses({ rental_mode: mode })
  const config = getRentalModeConfig(mode)

  return (
    <WarehouseGrid
      warehouses={warehouses}
      rentalMode={mode}
      title="Выберите удобный склад"
      emptyMessage={`Пока нет доступных складов для раздела «${config.label.toLowerCase()}».`}
    />
  )
}

export default async function RentalCatalogEntryPage({ params }: Props) {
  const { slug } = await params
  const mode = rentalModeFromCatalogSlug(slug)

  if (mode === null) {
    notFound()
  }

  const config = getRentalModeConfig(mode)

  return (
    <main>
      <section className="hero rental-entry-hero">
        <div className="container">
          <div className="hero-inner rental-entry-inner">
            <div className="hero-text">
              <p className="hero-eyebrow">Склады индивидуального хранения</p>
              <h1 className="hero-heading">
                {config.heroTitle}
              </h1>
              <p className="hero-sub">
                {config.heroDescription}
              </p>
              <div className="hero-ctas">
                <a href="#warehouses" className="btn-primary">Выбрать удобный склад</a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div id="warehouses" className="container warehouses-container">
        <Suspense fallback={<GridSkeleton />}>
          <WarehousesAsync mode={mode} />
        </Suspense>
      </div>
    </main>
  )
}
