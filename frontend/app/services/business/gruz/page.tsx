import type { Metadata } from 'next'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Хранение груза на складе',
  description: 'Хранение грузов и товаров на складе в Москве. Доступ 24/7, охрана и погрузочное оборудование.',
}

const features = [
  'Боксы до 60 кв. м под разные объёмы груза',
  'Тележки, рохли и гидравлический штабелёр',
  'Грузовые лифты для быстрой разгрузки',
  'Официальный договор и документы для бухгалтерии',
  'Контроль доступа для сотрудников',
  'Контроль температуры и влажности',
]

const stats = [
  { value: '9', label: 'складов в Москве' },
  { value: '60 кв. м', label: 'максимальный бокс' },
  { value: '24/7', label: 'доступ к грузу' },
  { value: '10 мин', label: 'оформление договора' },
]

export default function GruzPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для бизнеса', href: '/services/business' },
          { label: 'Хранение груза' },
        ]}
      />

      <section className="svc-hero">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для бизнеса / Груз</p>
            <h1 className="svc-title svc-title--compact">Хранение груза на складе</h1>
            <p className="svc-lead">
              Складские помещения для товаров и грузов с профессиональным оборудованием для погрузки и разгрузки.
            </p>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-grid svc-grid-cards">
            {features.map((feature) => (
              <article key={feature} className="svc-card">
                <span className="svc-card-icon" aria-hidden="true">
                  📦
                </span>
                <p className="svc-card-copy">
                  <strong>{feature}</strong>
                </p>
              </article>
            ))}
          </div>

          <div className="svc-stats" style={{ marginTop: '24px' }}>
            {stats.map((stat) => (
              <article key={stat.label} className="svc-stat">
                <span className="svc-stat-value">{stat.value}</span>
                <span className="svc-stat-label">{stat.label}</span>
              </article>
            ))}
          </div>
        </div>
      </section>

      <CallbackCta title="Получить коммерческое предложение" />
    </>
  )
}
