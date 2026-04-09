import type { Metadata } from 'next'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Хранение мебели',
  description: 'Хранение мебели на складе в Москве. Тёплые боксы, доступ 24/7 и безопасная упаковка.',
}

const reasons = [
  {
    title: 'Тёплые и сухие боксы',
    description: 'Поддерживаем стабильную температуру и сухой режим, чтобы мебель не пострадала от сырости и холода.',
  },
  {
    title: 'Боксы от 1 до 60 кв. м',
    description: 'Можно разместить как отдельные предметы, так и мебель из всей квартиры или офиса.',
  },
  {
    title: 'Доступ 24/7',
    description: 'Забрать или привезти вещи можно в удобное время без привязки к рабочему графику.',
  },
  {
    title: 'Охрана и видеонаблюдение',
    description: 'Каждый склад оборудован системами безопасности и контролем доступа.',
  },
]

const tips = [
  'Разберите мебель на части, если это возможно, чтобы сократить объём.',
  'Оберните фасады, углы и стеклянные элементы мягкими материалами или плёнкой.',
  'Не складывайте тяжёлые предметы на мягкую мебель.',
  'Используйте высоту бокса и стеллажи для рационального размещения.',
  'Оставьте проход к тем вещам, которые могут понадобиться в первую очередь.',
]

export default function MebeliPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для дома', href: '/services/storage' },
          { label: 'Хранение мебели' },
        ]}
      />

      <section className="svc-hero svc-hero--accent">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для дома / Мебель</p>
            <h1 className="svc-title svc-title--compact">Хранение мебели</h1>
            <p className="svc-lead">
              Надёжное хранение мебели любых размеров в тёплых и сухих боксах. Подходит для ремонта, переезда и временной
              разгрузки квартиры.
            </p>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container svc-two-col">
          <div>
            <div className="svc-section-heading">
              <p className="svc-eyebrow">Почему мы</p>
              <h2 className="svc-section-title">Почему мебель хранят у нас</h2>
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
            <aside className="svc-contact-panel">
              <h3 className="svc-panel-title">Заказать хранение</h3>
              <p className="svc-contact-copy">Рассчитаем стоимость и подберём бокс под ваш объём мебели.</p>
              <a href="tel:+74952663974" className="svc-btn-secondary">
                +7 (495) 266-39-74
              </a>
              <a href="mailto:info@alfasklad.ru" className="svc-btn-ghost">
                Написать на почту
              </a>
            </aside>

            <aside className="svc-note" style={{ marginTop: '18px' }}>
              <h3 className="svc-note-title">Советы по упаковке</h3>
              <div className="svc-bullets">
                {tips.map((tip, index) => (
                  <div key={tip} className="svc-bullet">
                    <span className="svc-bullet-mark">{index + 1}.</span>
                    <span>{tip}</span>
                  </div>
                ))}
              </div>
            </aside>
          </div>
        </div>
      </section>

      <CallbackCta title="Нужен бокс для мебели?" />
    </>
  )
}
