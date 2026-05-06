import type { Metadata } from 'next'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Хранение бытовой техники',
  description: 'Безопасное хранение бытовой техники в Москве с контролем температуры и влажности.',
}

const items = [
  'Телевизоры',
  'Компьютеры',
  'Стиральные машины',
  'Холодильники',
  'Посудомоечные машины',
  'Пылесосы',
  'Микроволновки',
  'Мелкая электроника',
]

const tips = [
  'Разморозьте и полностью просушите холодильник или морозильную камеру перед хранением.',
  'Упакуйте технику в оригинальную коробку или защитную плёнку.',
  'Смотайте кабели и зафиксируйте их отдельно, чтобы не повредить разъёмы.',
  'Не ставьте тяжёлые предметы на технику с хрупким корпусом или экраном.',
]

export default function TekhnikaPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для дома', href: '/services/storage' },
          { label: 'Хранение бытовой техники' },
        ]}
      />

      <section className="svc-hero svc-hero--accent">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для дома / Бытовая техника</p>
            <h1 className="svc-title svc-title--compact">Хранение бытовой техники</h1>
            <p className="svc-lead">
              Климат-контроль и охраняемый доступ помогают сохранить технику в рабочем состоянии даже при длительном
              хранении.
            </p>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-section-heading">
            <p className="svc-eyebrow">Подходит для</p>
            <h2 className="svc-section-title">Какую технику можно хранить</h2>
          </div>

          <div className="svc-grid svc-grid-tight">
            {items.map((item) => (
              <article key={item} className="svc-stat">
                <span className="svc-stat-value" style={{ fontSize: '24px' }}>
                  ⚙️
                </span>
                <span className="svc-stat-label">{item}</span>
              </article>
            ))}
          </div>

          <aside className="svc-note" style={{ marginTop: '24px' }}>
            <h3 className="svc-note-title">Важно перед сдачей техники на хранение</h3>
            <div className="svc-bullets">
              {tips.map((tip) => (
                <div key={tip} className="svc-bullet">
                  <span className="svc-bullet-mark">•</span>
                  <span>{tip}</span>
                </div>
              ))}
            </div>
          </aside>
        </div>
      </section>

      <CallbackCta title="Нужен бокс для техники?" />
    </>
  )
}
