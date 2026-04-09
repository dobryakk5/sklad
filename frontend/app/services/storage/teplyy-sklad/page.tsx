import type { Metadata } from 'next'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Тёплый склад',
  description: 'Аренда тёплого склада в Москве. Температура не ниже +12°С и контроль влажности круглый год.',
}

const cards = [
  {
    title: 'Температура от +12°C',
    description: 'Климат-контроль поддерживает стабильную температуру круглый год без риска для вещей.',
  },
  {
    title: 'Контроль влажности',
    description: 'Сухой воздух помогает защитить деревянную мебель, книги, технику и архивы.',
  },
  {
    title: 'Полная безопасность',
    description: 'Охрана, видеонаблюдение и контроль доступа обеспечивают сохранность вещей 24/7.',
  },
]

const items = [
  'Деревянная мебель',
  'Кожаные изделия',
  'Электроника',
  'Книги и документы',
  'Картины и антиквариат',
  'Музыкальные инструменты',
  'Вино',
  'Меха и одежда',
]

export default function TeplyySkladPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для дома', href: '/services/storage' },
          { label: 'Тёплый склад' },
        ]}
      />

      <section className="svc-hero svc-hero--accent">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для дома / Тёплый склад</p>
            <h1 className="svc-title svc-title--compact">Тёплый склад</h1>
            <p className="svc-lead">
              Отапливаемые боксы с контролем температуры и влажности. Подходят для имущества, которое чувствительно к
              перепадам климата.
            </p>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-grid svc-grid-cards">
            {cards.map((card) => (
              <article key={card.title} className="svc-card">
                <span className="svc-card-icon" aria-hidden="true">
                  🌡️
                </span>
                <h2 className="svc-card-title">{card.title}</h2>
                <p className="svc-card-copy">{card.description}</p>
              </article>
            ))}
          </div>

          <div className="svc-highlight" style={{ marginTop: '24px' }}>
            <h2 className="svc-highlight-title">Что подходит для тёплого хранения</h2>
            <div className="svc-pill-cloud">
              {items.map((item) => (
                <span key={item} className="svc-pill">
                  {item}
                </span>
              ))}
            </div>
          </div>
        </div>
      </section>

      <CallbackCta title="Арендовать тёплый склад" />
    </>
  )
}
