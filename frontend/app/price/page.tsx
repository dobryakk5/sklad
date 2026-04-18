import Link from 'next/link'
import type { Metadata } from 'next'
import FurnitureCalculator from '../../components/price/FurnitureCalculator'
import PriceTable from '../../components/price/PriceTable'
import styles from '../../components/price/price.module.css'

export const metadata: Metadata = {
  title: 'Цены на хранение вещей в Москве',
  description:
    'Стоимость аренды склада для хранения вещей и бизнеса в Москве. Боксы от 1 до 20 м², доступ 24/7, охрана, страховка, электричество включены в стоимость.',
}

const INCLUDED = [
  { icon: '🔐', label: 'Доступ по пин-коду' },
  { icon: '🛡️', label: 'Страховка' },
  { icon: '👮', label: 'Охрана' },
  { icon: '⚡', label: 'Электричество' },
  { icon: '🛒', label: 'Тележки' },
  { icon: '📶', label: 'Интернет' },
  { icon: '💡', label: 'Освещение' },
]

const FEATURES = [
  {
    icon: '🏠',
    title: 'Индивидуальная комната',
    description: 'Только ваши вещи, никаких посторонних и доступ по персональному коду.',
  },
  {
    icon: '📐',
    title: 'Высота до 3 метров',
    description: 'Можно использовать объём по максимуму и хранить крупногабаритные предметы.',
  },
  {
    icon: '🕐',
    title: 'Доступ 24/7',
    description: 'Приезжайте в удобное время без ограничений по графику или записи.',
  },
  {
    icon: '🔑',
    title: 'Ключ только у вас',
    description: 'Полная приватность и безопасность для личных вещей, мебели и товара.',
  },
]

const FAQ = [
  {
    question: 'Что входит в стоимость аренды?',
    answer:
      'В стоимость включены доступ по пин-коду, страховка имущества, круглосуточная охрана, электричество, интернет, освещение и использование тележек внутри склада.',
  },
  {
    question: 'Как оплатить аренду?',
    answer:
      'Оплату можно внести онлайн банковской картой, через личный кабинет, наличными в офисе склада или по безналичному расчёту для юридических лиц.',
  },
  {
    question: 'Какой минимальный срок аренды?',
    answer:
      'Базовый тариф рассчитан на месяц. При этом менеджер подскажет варианты под краткосрочное и длительное хранение, если нужен нестандартный срок.',
  },
  {
    question: 'Подойдёт ли бокс для крупной мебели и техники?',
    answer:
      'Да. Боксы с высотой потолка до 3 метров подходят для хранения мебели, бытовой техники, коробок, сезонных вещей и крупногабаритных предметов.',
  },
]

export default function PricePage() {
  return (
    <main className={styles.page}>
      <nav className={styles.breadcrumbs} aria-label="Хлебные крошки">
        <div className={`container ${styles.breadcrumbsInner}`}>
          <span className={styles.breadcrumbItem}>
            <Link href="/" className={styles.breadcrumbLink}>
              Главная
            </Link>
          </span>
          <span className={styles.breadcrumbItem}>
            <span className={styles.breadcrumbSeparator}>/</span>
            <span className={styles.breadcrumbCurrent}>Цены</span>
          </span>
        </div>
      </nav>

      <section className={styles.hero}>
        <div className={`container ${styles.heroInner}`}>
          <div>
            <p className={styles.eyebrow}>Цены на хранение</p>
            <h1 className={styles.title}>Стоимость аренды склада в Москве</h1>
            <p className={styles.lead}>
              Цена аренды склада указана за месяц в рублях, включая НДС. Указанные площади приведены для примера цен и
              не включают предложения по акциям. Актуальная информация о свободных боксах обновляется на странице{' '}
              <Link href="/online/">онлайн аренды</Link>.
            </p>
          </div>

          <div className={styles.includedBlock}>
            <p className={styles.includedLabel}>Что входит в стоимость аренды</p>
            <div className={styles.includedGrid}>
              {INCLUDED.map((item) => (
                <div key={item.label} className={styles.includedItem}>
                  <span className={styles.includedIcon} aria-hidden="true">
                    {item.icon}
                  </span>
                  <span>{item.label}</span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className={styles.section}>
        <div className="container">
          <div className={styles.infoGrid}>
            <article className={`${styles.card} ${styles.infoCard}`}>
              <h2 className={styles.cardTitle}>От чего зависит стоимость аренды склада</h2>
              <p className={styles.cardText}>
                АльфаСклад предоставляет в пользование ячейки размером от 1 м³ и полноценные боксы от 1 м² с высотой
                до 3 м. Стоимость зависит от размера бокса, расположения склада и выбранного формата хранения.
              </p>
            </article>

            <article className={`${styles.card} ${styles.infoCard}`}>
              <h2 className={styles.cardTitle}>Как подобрать оптимальный склад по площади и цене</h2>
              <p className={styles.cardText}>
                При выборе учитывайте месторасположение, наличие грузового лифта, ширину коридоров, тележки и
                оборудование для погрузки. Ниже есть калькулятор, который поможет быстро оценить нужный объём.
              </p>
            </article>
          </div>
        </div>
      </section>

      <section className={styles.ctaBand}>
        <div className={`container ${styles.ctaBandInner}`}>
          <div>
            <h2 className={styles.ctaBandTitle}>Не знаете, какой бокс выбрать?</h2>
            <p className={styles.ctaBandText}>
              Воспользуйтесь калькулятором ниже или позвоните нам. Поможем подобрать размер и склад за несколько минут.
            </p>
          </div>

          <div className={styles.ctaBandActions}>
            <a href="tel:+74952663974" className={styles.buttonSecondary}>
              +7 (495) 266-39-74
            </a>
            <Link href="/rental_catalog/" className={styles.buttonGhost}>
              Онлайн-аренда
            </Link>
          </div>
        </div>
      </section>

      <FurnitureCalculator />
      <PriceTable />

      <section className={styles.section}>
        <div className="container">
          <div className={styles.sectionHeading}>
            <p className={styles.eyebrow}>Преимущества</p>
            <h2 className={styles.sectionTitle}>Характеристики бокса</h2>
          </div>

          <div className={styles.featuresGrid}>
            {FEATURES.map((feature) => (
              <article key={feature.title} className={`${styles.card} ${styles.featureCard}`}>
                <div className={styles.featureIcon} aria-hidden="true">
                  {feature.icon}
                </div>
                <h3 className={styles.featureTitle}>{feature.title}</h3>
                <p className={styles.featureText}>{feature.description}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      <section className={styles.sectionMuted}>
        <div className="container">
          <div className={styles.sectionHeading}>
            <p className={styles.eyebrow}>FAQ</p>
            <h2 className={styles.sectionTitle}>Часто задаваемые вопросы</h2>
          </div>

          <div className={styles.faqList}>
            {FAQ.map((item) => (
              <details key={item.question} className={`${styles.card} ${styles.faqItem}`}>
                <summary>
                  <span>{item.question}</span>
                  <span className={styles.faqIcon}>+</span>
                </summary>
                <div className={styles.faqBody}>{item.answer}</div>
              </details>
            ))}
          </div>
        </div>
      </section>
    </main>
  )
}
