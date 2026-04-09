import type { Metadata } from 'next'
import Link from 'next/link'
import AdvantagesBlock from '@/components/services/AdvantagesBlock'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Услуги АльфаСклад',
  description: 'Услуги компании АльфаСклад: хранение для дома и бизнеса, доставка вещей на склад и обратно.',
}

const services = [
  {
    href: '/services/storage',
    icon: '🏠',
    title: 'Для дома',
    description:
      'Индивидуальные боксы для мебели, техники, сезонных вещей и личных предметов. Тёплые, чистые и надёжные помещения.',
    tags: ['Мебель', 'Бытовая техника', 'Тёплый склад', 'Сезонные вещи'],
  },
  {
    href: '/services/business',
    icon: '🏢',
    title: 'Для бизнеса',
    description:
      'Складские решения для компаний: хранение грузов, документов, офисной мебели и оборудования с удобным доступом 24/7.',
    tags: ['Груз', 'Документы', 'Оборудование', 'Офисная мебель'],
  },
  {
    href: '/services/delivery',
    icon: '🚚',
    title: 'Доставка',
    description:
      'Организуем перевозку вещей на склад и обратно по специальному тарифу. Грузчики и упаковка доступны в одном сценарии.',
    tags: ['Перевозка', 'Грузчики', 'Упаковка', 'Обратная доставка'],
  },
]

const extraServices = [
  {
    href: '/services/delivery',
    icon: '📦',
    title: 'Хранение вещей и мебели с доставкой',
    description: 'Бережно упакуем, отвезём на склад и по запросу вернём вещи обратно.',
  },
  {
    href: '/services/delivery',
    icon: '🚛',
    title: 'Доставка вещей',
    description: 'Уже выбрали бокс? Подключите доставку по специальному тарифу без поиска подрядчика.',
  },
  {
    href: 'tel:+74952663974',
    icon: '🗄️',
    title: 'Аренда стеллажей',
    description: 'Подскажем, какие стеллажи подойдут под ваш объём и как эффективнее использовать высоту бокса.',
  },
]

export default function ServicesPage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги' },
        ]}
      />

      <section className="svc-hero svc-hero--accent">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги АльфаСклад</p>
            <h1 className="svc-title">Услуги для хранения вещей и бизнеса</h1>
            <p className="svc-lead">
              Всё необходимое для удобного хранения: решения для дома, бизнеса и доставка до склада без отдельной
              логистики.
            </p>

            <div className="svc-actions">
              <Link href="/services/storage" className="svc-btn-primary">
                Для дома
              </Link>
              <Link href="/services/business" className="svc-btn-ghost">
                Для бизнеса
              </Link>
              <Link href="/services/delivery" className="svc-btn-ghost">
                Доставка
              </Link>
            </div>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-section-heading">
            <p className="svc-eyebrow">Основные направления</p>
            <h2 className="svc-section-title">Выберите подходящий сценарий хранения</h2>
            <p className="svc-section-subtitle">Каждый раздел ведёт к отдельным страницам с деталями и подсказками.</p>
          </div>

          <div className="svc-grid svc-grid-services">
            {services.map((service) => (
              <Link key={service.href} href={service.href} className="svc-card-link">
                <article className="svc-card">
                  <span className="svc-card-icon" aria-hidden="true">
                    {service.icon}
                  </span>
                  <h3 className="svc-card-title">{service.title}</h3>
                  <p className="svc-card-copy">{service.description}</p>
                  <div className="svc-card-tags">
                    {service.tags.map((tag) => (
                      <span key={tag} className="svc-tag">
                        {tag}
                      </span>
                    ))}
                  </div>
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
            <p className="svc-eyebrow">Дополнительно</p>
            <h2 className="svc-section-title">Сопутствующие сервисы</h2>
          </div>

          <div className="svc-grid svc-grid-cards">
            {extraServices.map((service) =>
              service.href.startsWith('/') ? (
                <Link key={service.title} href={service.href} className="svc-card-link">
                  <article className="svc-card">
                    <span className="svc-card-icon" aria-hidden="true">
                      {service.icon}
                    </span>
                    <h3 className="svc-card-title">{service.title}</h3>
                    <p className="svc-card-copy">{service.description}</p>
                    <span className="svc-card-cta">Узнать подробнее →</span>
                  </article>
                </Link>
              ) : (
                <a key={service.title} href={service.href} className="svc-card-link">
                  <article className="svc-card">
                    <span className="svc-card-icon" aria-hidden="true">
                      {service.icon}
                    </span>
                    <h3 className="svc-card-title">{service.title}</h3>
                    <p className="svc-card-copy">{service.description}</p>
                    <span className="svc-card-cta">Позвонить →</span>
                  </article>
                </a>
              ),
            )}
          </div>
        </div>
      </section>

      <AdvantagesBlock />
      <CallbackCta title="Нужна помощь с выбором услуги?" />
    </>
  )
}
