import { Suspense } from 'react'
import { getWarehouses } from '@/lib/api'
import { WarehouseGrid } from '@/components/home/WarehouseGrid'
import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Аренда боксов для хранения вещей в Москве',
  description:
    'Боксы от 1 до 76 м² в Москве. Доступ 24/7, охрана, видеонаблюдение. Выберите склад рядом с домом и арендуйте онлайн.',
}

const STATS = [
  { value: '12',      label: 'складов в Москве' },
  { value: '1400+',   label: 'боксов в наличии' },
  { value: 'от 1 м²', label: 'минимальный размер' },
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
  const warehouses = await getWarehouses()
  return <WarehouseGrid warehouses={warehouses} />
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
                Аренда боксов<br />
                <span className="hero-accent">в Москве</span>
              </h1>
              <p className="hero-sub">
                Для личных вещей и бизнеса. Отапливаемые боксы,
                видеонаблюдение, круглосуточный доступ по пин-коду.
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
                ['🌡', 'Отапливаемые боксы'],
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

      <div id="warehouses" className="container warehouses-container">
        <Suspense fallback={<GridSkeleton />}>
          <WarehousesAsync />
        </Suspense>
      </div>

      <section className="cta-band">
        <div className="container cta-band-inner">
          <div>
            <p className="cta-band-title">Не знаете какой бокс выбрать?</p>
            <p className="cta-band-sub">Менеджер поможет подобрать подходящий размер</p>
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
