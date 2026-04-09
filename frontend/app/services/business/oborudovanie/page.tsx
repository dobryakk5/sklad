import type { Metadata } from 'next'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Хранение оборудования',
  description: 'Хранение производственного и офисного оборудования в Москве с гибкими условиями аренды.',
}

const items = [
  'Производственное оборудование',
  'Серверы и IT-оборудование',
  'Телекоммуникационное оборудование',
  'Инструменты и оснастка',
  'Офисная техника',
  'Запчасти и автокомпоненты',
]

export default function OborudovaniePage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для бизнеса', href: '/services/business' },
          { label: 'Хранение оборудования' },
        ]}
      />

      <section className="svc-hero">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для бизнеса / Оборудование</p>
            <h1 className="svc-title svc-title--compact">Хранение оборудования</h1>
            <p className="svc-lead">
              Профессиональное хранение производственного, офисного и специализированного оборудования любого размера.
            </p>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-icon-list">
            {items.map((item) => (
              <article key={item} className="svc-icon-card">
                <span className="svc-icon-card-icon" aria-hidden="true">
                  ⚙️
                </span>
                <span className="svc-icon-card-label">{item}</span>
              </article>
            ))}
          </div>

          <div className="svc-highlight" style={{ marginTop: '24px' }}>
            <h2 className="svc-highlight-title">Специальные условия для больших объёмов</h2>
            <p className="svc-highlight-copy">
              При аренде бокса от 20 кв. м обсуждаем скидку и персональный сценарий размещения оборудования.
            </p>
            <div className="svc-actions" style={{ justifyContent: 'center' }}>
              <a href="tel:+74952663974" className="svc-btn-primary">
                Обсудить условия
              </a>
            </div>
          </div>
        </div>
      </section>

      <CallbackCta title="Подберём бокс под ваше оборудование" />
    </>
  )
}
