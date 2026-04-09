import type { Metadata } from 'next'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Доставка вещей на склад',
  description: 'Организуем доставку вещей на склад и обратно. Грузчики, упаковка и понятный сценарий перевозки.',
}

const steps = [
  {
    title: 'Оставьте заявку',
    description: 'Позвоните или напишите. Уточним объём вещей, адрес и удобное время перевозки.',
  },
  {
    title: 'Выберите бокс',
    description: 'Подберём размер бокса под ваши вещи. Можно ориентироваться по каталогу или через менеджера.',
  },
  {
    title: 'Приедем и упакуем',
    description: 'В назначенное время команда поможет упаковать вещи и аккуратно загрузит их в машину.',
  },
  {
    title: 'Доставим на склад',
    description: 'Привезём имущество на склад и разместим его в боксе с учётом сохранности и доступа.',
  },
  {
    title: 'Вернём по запросу',
    description: 'Когда вещи снова понадобятся, можно организовать обратную доставку.',
  },
]

const included = [
  'Профессиональные грузчики',
  'Упаковочные материалы',
  'Грузовой автомобиль',
  'Разборка крупной мебели',
  'Размещение на складе',
  'Обратная доставка',
]

export default function DeliveryPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Доставка' },
        ]}
      />

      <section className="svc-hero svc-hero--delivery">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Доставка</p>
            <h1 className="svc-title svc-title--compact">Доставка вещей на склад</h1>
            <p className="svc-lead">
              Если бокс уже выбран, поможем организовать перевозку вещей на склад и обратно без поиска отдельной логистики.
            </p>

            <div className="svc-actions">
              <a href="tel:+74952663974" className="svc-btn-secondary">
                Узнать стоимость доставки
              </a>
            </div>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-section-heading">
            <p className="svc-eyebrow">Пошагово</p>
            <h2 className="svc-section-title">Как это работает</h2>
            <p className="svc-section-subtitle">Сценарий доставки и хранения «под ключ» без лишних промежуточных этапов.</p>
          </div>

          <div className="svc-steps">
            {steps.map((step, index) => (
              <article key={step.title} className="svc-step">
                <span className="svc-step-num">{String(index + 1).padStart(2, '0')}</span>
                <div>
                  <h3 className="svc-step-title">{step.title}</h3>
                  <p className="svc-step-copy">{step.description}</p>
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>

      <section className="svc-section svc-section-muted">
        <div className="container">
          <div className="svc-section-heading">
            <p className="svc-eyebrow">Входит в услугу</p>
            <h2 className="svc-section-title">Что можно включить в перевозку</h2>
          </div>

          <div className="svc-icon-list">
            {included.map((item) => (
              <article key={item} className="svc-icon-card svc-icon-card--center">
                <span className="svc-icon-card-icon" aria-hidden="true">
                  🚚
                </span>
                <span className="svc-icon-card-label">{item}</span>
              </article>
            ))}
          </div>
        </div>
      </section>

      <CallbackCta title="Рассчитать стоимость доставки" />
    </>
  )
}
