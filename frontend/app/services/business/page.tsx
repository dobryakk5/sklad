import type { Metadata } from 'next'
import Link from 'next/link'
import AdvantagesBlock from '@/components/services/AdvantagesBlock'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Хранение для бизнеса',
  description: 'Складские решения для компаний: грузы, документы, оборудование и отдельные сценарии хранения в Москве.',
}

const subServices = [
  {
    href: '/services/business/gruz',
    icon: '📦',
    title: 'Хранение груза',
    description: 'Складские помещения для товаров и грузов любых габаритов с удобным доступом.',
  },
  {
    href: '/services/business/dokumenty',
    icon: '🗃️',
    title: 'Хранение документов',
    description: 'Безопасный архив для деловой документации с возможностью оперативного доступа.',
  },
  {
    href: '/services/business/oborudovanie',
    icon: '⚙️',
    title: 'Хранение оборудования',
    description: 'Хранение производственного, офисного и специализированного оборудования.',
  },
  {
    href: '/services/business',
    icon: '🪑',
    title: 'Офисная мебель',
    description: 'Отдельный сценарий для переезда, ремонта или реорганизации офисного пространства.',
  },
]

const benefits = [
  {
    title: 'Договор с юрлицом',
    description: 'Заключаем официальный договор с юридическими лицами и быстро готовим комплект документов.',
  },
  {
    title: 'Безналичная оплата',
    description: 'Принимаем оплату на расчётный счёт и предоставляем закрывающие документы.',
  },
  {
    title: 'Доступ сотрудников',
    description: 'Можно оформить доступ нескольким сотрудникам без дополнительной логистики на стороне клиента.',
  },
  {
    title: 'Гибкие тарифы',
    description: 'Для долгосрочного хранения и больших объёмов доступны персональные условия.',
  },
]

export default function BusinessPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для бизнеса' },
        ]}
      />

      <section className="svc-hero">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для бизнеса</p>
            <h1 className="svc-title svc-title--compact">Складские решения для бизнеса</h1>
            <p className="svc-lead">
              Профессиональные услуги хранения для компаний любого масштаба: договор, безналичная оплата и прозрачный
              доступ к складу.
            </p>

            <div className="svc-actions">
              <a href="tel:+74952663974" className="svc-btn-primary">
                Позвонить менеджеру
              </a>
              <a href="mailto:info@alfasklad.ru" className="svc-btn-ghost">
                Отправить запрос
              </a>
            </div>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-section-heading">
            <p className="svc-eyebrow">Категории хранения</p>
            <h2 className="svc-section-title">Выберите тип хранения для бизнеса</h2>
          </div>

          <div className="svc-grid svc-grid-cards">
            {subServices.map((service) => (
              <Link key={service.href + service.title} href={service.href} className="svc-card-link">
                <article className="svc-card">
                  <span className="svc-card-icon" aria-hidden="true">
                    {service.icon}
                  </span>
                  <h3 className="svc-card-title">{service.title}</h3>
                  <p className="svc-card-copy">{service.description}</p>
                  <span className="svc-card-cta">Подробнее →</span>
                </article>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <section className="svc-section svc-section-muted">
        <div className="container">
          <div className="svc-section-heading">
            <p className="svc-eyebrow">Для юридических лиц</p>
            <h2 className="svc-section-title">Что получает компания</h2>
          </div>

          <div className="svc-grid svc-grid-cards">
            {benefits.map((benefit) => (
              <article key={benefit.title} className="svc-card">
                <span className="svc-card-icon" aria-hidden="true">
                  🧾
                </span>
                <h3 className="svc-card-title">{benefit.title}</h3>
                <p className="svc-card-copy">{benefit.description}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      <AdvantagesBlock />
      <CallbackCta title="Подберём склад для вашего бизнеса" />
    </>
  )
}
