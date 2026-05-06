import Link from 'next/link'
import { Suspense } from 'react'
import { getReviews, getWarehouses } from '@/lib/api'
import { ReviewsSection } from '@/components/home/ReviewsSection'
import { WarehouseGrid } from '@/components/home/WarehouseGrid'
import type { Metadata } from 'next'
import type { Warehouse } from '@/types/warehouse'

export const metadata: Metadata = {
  title: 'Аренда боксов, контейнеров и кладовок в Москве',
  description:
    'Боксы, контейнеры, ячейки, кладовки и помещения в Москве. Доступ 24/7, охрана и удобный выбор склада рядом с домом или бизнесом.',
}

const STATS = [
  { value: '12',      label: 'складов в Москве' },
  { value: '1400+',   label: 'помещений в каталоге' },
  { value: '5',       label: 'типов хранения' },
  { value: '24/7',    label: 'доступ без выходных' },
]

function GridSkeleton() {
  return (
    <div className="warehouse-grid">
      {Array.from({ length: 6 }).map((_, i) => (
        <div key={i} className="card-skeleton" />
      ))}
    </div>
  )
}

async function WarehousesAsync() {
  let warehouses: Warehouse[] = []

  try {
    warehouses = await getWarehouses()
  } catch {
    warehouses = []
  }

  return <WarehouseGrid warehouses={warehouses} />
}

async function ReviewsAsync() {
  const reviews = await getReviews()
  return <ReviewsSection reviews={reviews} />
}

export default function HomePage() {
  return (
    <main>
      <section className="hero">
        <div className="container">
          <div className="hero-inner">
            <div className="hero-text">
              <p className="hero-eyebrow">Сеть складов индивидуального хранения</p>
              <h1 className="hero-heading">
                Аренда помещений<br />
                <span className="hero-accent">в Москве</span>
              </h1>
              <p className="hero-sub">
                Для личных вещей и бизнеса. Боксы, контейнеры, ячейки,
                кладовки и помещения с круглосуточным доступом по пин-коду.
              </p>
              <div className="hero-ctas">
                <a href="#warehouses" className="btn-primary">Выбрать склад</a>
                <a
                  href="https://alfasklad.ru/price/"
                  className="btn-ghost"
                  target="_blank"
                  rel="noopener"
                >
                  Калькулятор цены →
                </a>
              </div>
            </div>

            <ul className="hero-usps" aria-label="Преимущества">
              {[
                ['🔒', 'Охрана и видеонаблюдение'],
                ['📦', 'Боксы, контейнеры и кладовки'],
                ['📱', 'Оплата онлайн и в приложении'],
                ['🚗', 'Удобный подъезд и погрузка'],
              ].map(([icon, text]) => (
                <li key={text} className="usp-item">
                  <span className="usp-icon" aria-hidden="true">{icon}</span>
                  <span>{text}</span>
                </li>
              ))}
            </ul>
          </div>

          <div className="stats-row" role="list">
            {STATS.map(({ value, label }) => (
              <div key={label} className="stat-item" role="listitem">
                <span className="stat-value">{value}</span>
                <span className="stat-label">{label}</span>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="online-discount">
        <div className="container">
          <div className="online-discount-card">
            <p className="online-discount-text">
              <span className="online-discount-badge">Скидка</span>
              При онлайн-аренде бокса оформление проходит полностью на сайте, без звонка и визита в офис.
            </p>

            <Link href="/online" className="btn-primary online-discount-cta">
              Онлайн-аренда
            </Link>
          </div>
        </div>
      </section>

      <div id="warehouses" className="container warehouses-container">
        <Suspense fallback={<GridSkeleton />}>
          <WarehousesAsync />
        </Suspense>
      </div>

      <Suspense fallback={null}>
        <ReviewsAsync />
      </Suspense>

      <section className="cta-band">
        <div className="container cta-band-inner">
          <div>
            <p className="cta-band-title">Не знаете какое помещение выбрать?</p>
            <p className="cta-band-sub">Менеджер поможет подобрать склад и подходящий размер под ваши вещи</p>
          </div>
          <div className="cta-band-actions">
            <a href="tel:+74952663974" className="btn-primary">Позвонить</a>
            <a
              href="https://alfasklad.ru/price/"
              className="btn-outline-light"
              target="_blank"
              rel="noopener"
            >
              Калькулятор площади
            </a>
          </div>
        </div>
      </section>
    </main>
  )
}
