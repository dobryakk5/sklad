import type { Metadata } from 'next'
import Link from 'next/link'
import AdvantagesBlock from '@/components/services/AdvantagesBlock'
import Breadcrumb from '@/components/services/Breadcrumb'
import CallbackCta from '@/components/services/CallbackCta'

export const metadata: Metadata = {
  title: 'Склады для хранения личных вещей',
  description: 'Индивидуальное хранение вещей на складе в Москве. Тёплые чистые боксы от 1 кв. м.',
}

const subServices = [
  {
    href: '/services/storage/mebeli',
    icon: '🪑',
    title: 'Хранение мебели',
    description: 'Надёжное хранение мебели любых размеров в тёплых и чистых боксах.',
  },
  {
    href: '/services/storage/tekhnika',
    icon: '📺',
    title: 'Хранение бытовой техники',
    description: 'Безопасное хранение бытовой техники с контролем температуры и влажности.',
  },
  {
    href: '/services/storage/teplyy-sklad',
    icon: '🌡️',
    title: 'Тёплый склад',
    description: 'Отапливаемые боксы для вещей, которые чувствительны к перепадам температуры.',
  },
  {
    href: '/services/storage',
    icon: '❄️',
    title: 'Сезонное хранение',
    description: 'Шины, велосипеды, лыжи и другой сезонный инвентарь можно хранить в индивидуальном боксе.',
  },
]

const useCases = [
  'Хранение мебели',
  'Хранение шин',
  'Тёплый склад',
  'Хранение вещей',
  'Хранение при переезде',
  'Хранение во время ремонта',
  'Хранение бытовой техники',
  'Хранение велосипедов',
  'Сезонное хранение',
  'Хранение книг',
  'Хранение детских вещей',
  'Временное хранение',
]

const stats = [
  { value: '9', label: 'складов в Москве' },
  { value: '1–60', label: 'кв. м в аренду' },
  { value: '24/7', label: 'доступ к боксу' },
  { value: '2,8 м', label: 'высота потолков' },
]

export default function StoragePage() {
  return (
    <>
      <Breadcrumb
        items={[
          { label: 'Главная', href: '/' },
          { label: 'Услуги', href: '/services' },
          { label: 'Для дома' },
        ]}
      />

      <section className="svc-hero svc-hero--accent">
        <div className="container">
          <div className="svc-hero-inner">
            <p className="svc-eyebrow">Услуги / Для дома</p>
            <h1 className="svc-title svc-title--compact">Склады индивидуального хранения</h1>
            <p className="svc-lead">
              Тёплые, чистые и надёжные боксы для мебели, техники и личных вещей. Поможем подобрать размер под ваш объём.
            </p>

            <div className="svc-actions">
              <Link href="/rental_catalog/arenda_kladovki/" className="svc-btn-primary">
                Арендовать бокс
              </Link>
              <a href="tel:+74952663974" className="svc-btn-ghost">
                Позвонить менеджеру
              </a>
            </div>
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container">
          <div className="svc-section-heading">
            <p className="svc-eyebrow">Категории</p>
            <h2 className="svc-section-title">Что можно хранить</h2>
            <p className="svc-section-subtitle">Выберите нужный сценарий, чтобы посмотреть детали и советы.</p>
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
            <p className="svc-eyebrow">Применение</p>
            <h2 className="svc-section-title">Популярные сценарии использования бокса</h2>
          </div>

          <div className="svc-pill-cloud">
            {useCases.map((item) => (
              <span key={item} className="svc-pill">
                {item}
              </span>
            ))}
          </div>
        </div>
      </section>

      <section className="svc-section">
        <div className="container svc-two-col-content">
          <div>
            <div className="svc-section-heading">
              <p className="svc-eyebrow">Как это работает</p>
              <h2 className="svc-section-title">Поможем организовать хранение личных вещей</h2>
            </div>

            <p className="svc-copy">
              АльфаСклад предоставляет в аренду индивидуальные складские комнаты от 1 до 60 кв. м. Подойдёт для мебели,
              книг, картин, техники и других личных вещей.
            </p>
            <p className="svc-copy">
              Если нужно, подскажем с упаковкой, расходниками и логистикой. Вы можете начать с выбора подходящей кладовки
              в каталоге или связаться с менеджером по телефону.
            </p>

            <div className="svc-actions">
              <Link href="/rental_catalog/arenda_kladovki/" className="svc-btn-primary">
                Арендовать сейчас
              </Link>
              <a href="tel:+74952663974" className="svc-btn-secondary">
                +7 (495) 266-39-74
              </a>
            </div>
          </div>

          <div className="svc-stats">
            {stats.map((stat) => (
              <article key={stat.label} className="svc-stat">
                <span className="svc-stat-value">{stat.value}</span>
                <span className="svc-stat-label">{stat.label}</span>
              </article>
            ))}
          </div>
        </div>
      </section>

      <AdvantagesBlock />
      <CallbackCta title="Подберём бокс под ваши вещи" />
    </>
  )
}
