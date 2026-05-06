import Link from 'next/link'
import { getReviews, getWarehouses } from '@/lib/api'
import { getRentalCatalogPath, getRentalModeConfig } from '@/lib/rentalModes'
import { WarehouseGrid } from '@/components/home/WarehouseGrid'
import { ReviewsSection } from '@/components/home/ReviewsSection'
import { RentalTabs } from './RentalTabs'
import { getRentalLandingContent } from '@/lib/rentalLandingContent'
import type { RentalMode } from '@/types/rental'
import styles from './RentalLanding.module.css'

const rentalModes: RentalMode[] = ['box', 'container', 'cell', 'storage', 'room']

function phoneHref() {
  return 'tel:+74952663974'
}

export async function RentalLandingPage({ mode }: { mode: RentalMode }) {
  const content = getRentalLandingContent(mode)
  const [warehouses, reviews] = await Promise.all([
    getWarehouses({ rental_mode: mode }),
    getReviews(),
  ])

  const visibleWarehouses = mode === 'cell'
    ? warehouses.filter((warehouse) => warehouse.available_boxes_count > 0)
    : warehouses
  const modeConfig = getRentalModeConfig(mode)
  const relatedModes = rentalModes.filter((item) => item !== mode)

  return (
    <main className={styles.page}>
      <section className={styles.hero}>
        <div className="container">
          <div className={styles.heroContent}>
            <div className={styles.breadcrumb}>
              <Link href="/">Главная</Link>
              <span>›</span>
              {mode === 'box' ? (
                <span className={styles.breadcrumbCurrent}>Аренда боксов</span>
              ) : (
                <>
                  <Link href="/rental_catalog/">Аренда боксов</Link>
                  <span>›</span>
                  <span className={styles.breadcrumbCurrent}>{modeConfig.label}</span>
                </>
              )}
            </div>

            <p className={styles.heroEyebrow}>{content.hero.eyebrow}</p>
            <h1 className={styles.heroTitle}>{content.hero.title}</h1>
            <p className={styles.heroDescription}>{content.hero.description}</p>

            <div className={styles.heroActions}>
              <a href="#warehouses" className={styles.buttonPrimary}>
                {content.hero.primaryLabel}
              </a>
              <a href={phoneHref()} className={styles.buttonSecondary}>
                {content.hero.secondaryLabel}
              </a>
            </div>

            <div className={styles.heroBadges}>
              {content.hero.badges.map((badge) => (
                <span key={badge} className={styles.heroBadge}>
                  {badge}
                </span>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className={styles.section}>
        <div className="container">
          <RentalTabs />

          <div className={styles.introGrid}>
            <div className={styles.introContent}>
              <h2>{content.introTitle}</h2>
              <p>{content.introDescription}</p>
            </div>

            {content.asideCard && (
              <aside className={styles.asideCard}>
                <h3 className={styles.asideCardTitle}>{content.asideCard.title}</h3>
                <ul>
                  {content.asideCard.items.map((item) => (
                    <li key={item}>{item}</li>
                  ))}
                </ul>
              </aside>
            )}
          </div>

          <div className={styles.cardsHeader}>
            <h2>{content.cardsTitle}</h2>
            {content.cardsDescription && <p>{content.cardsDescription}</p>}
          </div>

          <div className={styles.cardsGrid}>
            {content.cards.map((card) => (
              <article key={card.title} className={styles.card}>
                <div className={styles.cardIcon} aria-hidden="true">
                  {card.emoji}
                </div>
                {card.badge && <span className={styles.cardBadge}>{card.badge}</span>}
                <h3 className={styles.cardTitle}>{card.title}</h3>
                <div className={styles.cardMeta}>
                  {card.meta.map((item) => (
                    <span key={item} className={styles.cardMetaItem}>
                      {item}
                    </span>
                  ))}
                </div>
                <p className={styles.cardDescription}>{card.description}</p>
                <div className={styles.cardFooter}>
                  <div>
                    <span className={styles.cardPriceLabel}>Стоимость</span>
                    <span className={styles.cardPriceValue}>{card.price}</span>
                  </div>
                  <a href="#warehouses" className={styles.cardCta}>
                    Выбрать склад
                  </a>
                </div>
              </article>
            ))}
          </div>

          {content.note && (
            <div className={styles.noteBox}>
              {content.note}
            </div>
          )}
        </div>
      </section>

      <section id="warehouses" className={styles.sectionMuted}>
        <div className="container">
          <div className={styles.sectionHeading}>
            <h2>Выберите удобный склад</h2>
            <p className={styles.warehouseLead}>{content.warehouseLead}</p>
          </div>

          <WarehouseGrid
            warehouses={visibleWarehouses}
            rentalMode={mode}
            title={`Склады для раздела «${modeConfig.label.toLowerCase()}»`}
            emptyMessage={`Пока нет доступных складов для раздела «${modeConfig.label.toLowerCase()}».`}
          />
        </div>
      </section>

      {content.audience && (
        <section className={styles.section}>
          <div className="container">
            <div className={styles.sectionHeading}>
              <h2>{content.audience.title}</h2>
            </div>
            <div className={styles.audienceGrid}>
              {content.audience.items.map((item) => (
                <div key={item} className={styles.audienceCard}>
                  {item}
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      <section className={styles.section}>
        <div className="container">
          <div className={styles.sectionHeading}>
            <h2>{content.featuresTitle}</h2>
          </div>
          <div className={styles.featuresGrid}>
            {content.features.map((feature) => (
              <article key={feature.title} className={styles.featureCard}>
                <div className={styles.featureHeader}>
                  <span className={styles.featureIcon} aria-hidden="true">
                    {feature.icon}
                  </span>
                  <h3 className={styles.featureTitle}>{feature.title}</h3>
                </div>
                <p className={styles.featureText}>{feature.text}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      {content.steps && (
        <section className={styles.sectionMuted}>
          <div className="container">
            <div className={styles.sectionHeading}>
              <h2>{content.steps.title}</h2>
            </div>
            <div className={styles.stepsGrid}>
              {content.steps.items.map((step) => (
                <article key={step.num} className={styles.stepCard}>
                  <span className={styles.stepNumber}>{step.num}</span>
                  <h3 className={styles.stepTitle}>{step.title}</h3>
                  <p className={styles.stepText}>{step.text}</p>
                </article>
              ))}
            </div>
          </div>
        </section>
      )}

      {content.rules && (
        <section className={styles.section}>
          <div className="container">
            <div className={styles.rulesGrid}>
              <div className={styles.rulesCard}>
                <h2 className={styles.rulesCardTitle}>{content.rules.allowedTitle}</h2>
                <p>{content.rules.allowedDescription}</p>
                <ul className={`${styles.rulesList} ${styles.rulesAllowed}`}>
                  {content.rules.allowed.map((item) => (
                    <li key={item}>{item}</li>
                  ))}
                </ul>
              </div>

              <div className={styles.rulesCard}>
                <h2 className={styles.rulesCardTitle}>{content.rules.forbiddenTitle}</h2>
                <p>{content.rules.forbiddenDescription}</p>
                <ul className={`${styles.rulesList} ${styles.rulesForbidden}`}>
                  {content.rules.forbidden.map((item) => (
                    <li key={item}>{item}</li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
        </section>
      )}

      <section className={styles.sectionMuted}>
        <div className="container">
          <div className={styles.sectionHeading}>
            <h2>{content.faqTitle}</h2>
          </div>
          <div className={styles.faqList}>
            {content.faqs.map((item) => (
              <details key={item.question} className={styles.faqItem}>
                <summary className={styles.faqQuestion}>{item.question}</summary>
                <div
                  className={styles.faqAnswer}
                  dangerouslySetInnerHTML={{ __html: item.answer }}
                />
              </details>
            ))}
          </div>
        </div>
      </section>

      <ReviewsSection reviews={reviews.slice(0, 6)} />

      <section className={styles.section}>
        <div className="container">
          <div className={styles.sectionHeading}>
            <h2>Другие форматы аренды</h2>
          </div>
          <div className={styles.relatedGrid}>
            {relatedModes.map((item) => {
              const relatedConfig = getRentalModeConfig(item)
              const relatedContent = getRentalLandingContent(item)

              return (
                <Link
                  key={item}
                  href={getRentalCatalogPath(item)}
                  className={styles.relatedCard}
                >
                  <span className={styles.relatedIcon} aria-hidden="true">
                    {relatedContent.cards[0]?.emoji ?? '📦'}
                  </span>
                  <span className={styles.relatedLabel}>{relatedConfig.label}</span>
                  <span className={styles.relatedArrow}>Открыть раздел →</span>
                </Link>
              )
            })}
          </div>
        </div>
      </section>

      <section className={styles.sectionMuted}>
        <div className="container">
          <div className={styles.contactBanner}>
            <div>
              <h2 className={styles.contactHeading}>{content.contact.title}</h2>
              <p className={styles.contactText}>{content.contact.text}</p>
            </div>
            <div className={styles.contactActions}>
              <a href={phoneHref()} className={styles.buttonPrimary}>
                Позвонить нам
              </a>
              <a href="#warehouses" className={styles.buttonSecondary}>
                Посмотреть склады
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>
  )
}
