import type { Metadata } from 'next'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Хранение документов',
  description: 'Безопасный архив для документов компании в Москве с круглосуточным доступом и официальным договором.',
}

const reasons = [
  {
    title: 'Юридическое требование',
    description: 'Помогаем соблюдать сроки хранения документов, не загромождая офис архивом.',
  },
  {
    title: 'Экономия пространства',
    description: 'Дорогие офисные площади остаются под рабочие задачи, а не под коробки с бумагами.',
  },
  {
    title: 'Безопасность',
    description: 'Архив хранится на охраняемой территории с видеонаблюдением и контролем доступа.',
  },
  {
    title: 'Оперативный доступ',
    description: 'Забрать нужные документы можно без длинных согласований и задержек.',
  },
]

const documents = [
  'Бухгалтерская документация',
  'Кадровые документы и личные дела',
  'Договоры и контракты',
  'Налоговые декларации',
  'Учредительные документы',
  'Судебные архивы',
  'Проектная документация',
]

export default function DokumentyPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для бизнеса', href: '/services/business' },
          { label: 'Хранение документов' },
        ]}
      />

      <section className="svc-hero">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для бизнеса / Документы</p>
            <h1 className="svc-title svc-title--compact">Хранение документов компании</h1>
            <p className="svc-lead">
              Надёжный архив для деловых бумаг с возможностью оперативного доступа в удобное время.
            </p>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container svc-two-col-even">
          <div>
            <div className="svc-section-heading">
              <p className="svc-eyebrow">Преимущества</p>
              <h2 className="svc-section-title">Зачем хранить документы на складе</h2>
            </div>

            <div className="svc-list">
              {reasons.map((item) => (
                <article key={item.title} className="svc-list-item">
                  <h3 className="svc-list-title">{item.title}</h3>
                  <p className="svc-list-copy">{item.description}</p>
                </article>
              ))}
            </div>
          </div>

          <div>
            <aside className="svc-panel">
              <h3 className="svc-panel-title">Что можно хранить</h3>
              <div className="svc-bullets">
                {documents.map((item) => (
                  <div key={item} className="svc-bullet">
                    <span className="svc-bullet-mark">✓</span>
                    <span>{item}</span>
                  </div>
                ))}
              </div>
            </aside>

            <aside className="svc-contact-panel" style={{ marginTop: '18px' }}>
              <h3 className="svc-panel-title">Минимальный объём аренды</h3>
              <p className="svc-contact-copy">Начать можно от 1 кв. м с официальным договором и понятным тарифом.</p>
              <a href="tel:+74952663974" className="svc-btn-secondary">
                Узнать стоимость
              </a>
            </aside>
          </div>
        </div>
      </section>

      <CallbackCta title="Организуем хранение вашего архива" />
    </>
  )
}
