'use client'

import Link from 'next/link'
import { useState } from 'react'
import styles from './price.module.css'

type FurnitureItem = {
  id: string
  label: string
  volume: number
  emoji?: string
  icon?: 'table'
}

const ITEMS: FurnitureItem[] = [
  { id: 'bed2', label: 'Кровать двуспальная', volume: 2, emoji: '🛏️' },
  { id: 'bed1', label: 'Кровать односпальная', volume: 1.2, emoji: '🛏️' },
  { id: 'nightstand', label: 'Тумбочка', volume: 0.1, emoji: '🗄️' },
  { id: 'dresser', label: 'Комод', volume: 0.4, emoji: '🗄️' },
  { id: 'wardrobe2', label: 'Шкаф 2-створчатый', volume: 0.8, emoji: '🚪' },
  { id: 'wardrobe3', label: 'Шкаф 3-створчатый', volume: 1.2, emoji: '🚪' },
  { id: 'wardrobe4', label: 'Шкаф 4-створчатый', volume: 1.6, emoji: '🚪' },
  { id: 'sofa2', label: 'Диван двухместный', volume: 1.5, emoji: '🛋️' },
  { id: 'sofa3', label: 'Диван трёхместный', volume: 2, emoji: '🛋️' },
  { id: 'armchair', label: 'Кресло', volume: 0.6, emoji: '💺' },
  { id: 'tv', label: 'Телевизор', volume: 0.2, emoji: '📺' },
  { id: 'piano', label: 'Пианино', volume: 1, emoji: '🎹' },
  { id: 'desk', label: 'Стол письменный', volume: 0.5, icon: 'table' },
  { id: 'table', label: 'Стол обеденный', volume: 0.8, icon: 'table' },
  { id: 'chair', label: 'Стул', volume: 0.15, emoji: '🪑' },
  { id: 'fridge2', label: 'Холодильник двухкамерный', volume: 0.5, emoji: '🧊' },
  { id: 'fridge1', label: 'Холодильник маленький', volume: 0.2, emoji: '🧊' },
  { id: 'washer', label: 'Стиральная машинка', volume: 0.3, emoji: '🫧' },
  { id: 'dishwasher', label: 'Посудомоечная машина', volume: 0.3, emoji: '🫧' },
  { id: 'appliance', label: 'Мелкая бытовая техника', volume: 0.1, emoji: '🔌' },
  { id: 'bicycle', label: 'Велосипед', volume: 0.4, emoji: '🚲' },
  { id: 'ski', label: 'Лыжи', volume: 0.3, emoji: '⛷️' },
  { id: 'stroller', label: 'Коляска детская', volume: 0.5, emoji: '👶' },
  { id: 'suitcase', label: 'Чемодан', volume: 0.1, emoji: '🧳' },
  { id: 'tires', label: 'Комплект шин', volume: 0.3, emoji: '🛞' },
  { id: 'box_sm', label: 'Коробка малая', volume: 0.04, emoji: '📦' },
  { id: 'box_lg', label: 'Коробка большая', volume: 0.12, emoji: '📦' },
  { id: 'box_arch', label: 'Коробка архивная', volume: 0.07, emoji: '🗃️' },
  { id: 'box_clothes', label: 'Короб для одежды', volume: 0.15, emoji: '👔' },
  { id: 'pallet', label: 'Европаллета', volume: 0.3, emoji: '🟫' },
]

const BOX_SIZES = [
  { area: 1, volume: 3, price: 2900 },
  { area: 2, volume: 6, price: 5200 },
  { area: 3, volume: 9, price: 7100 },
  { area: 4, volume: 12, price: 8900 },
  { area: 6, volume: 18, price: 12500 },
  { area: 8, volume: 24, price: 15800 },
  { area: 10, volume: 30, price: 18900 },
  { area: 15, volume: 45, price: 26500 },
  { area: 20, volume: 60, price: 33000 },
]

function TableIcon() {
  return (
    <svg viewBox="0 0 32 32" aria-hidden="true" className={styles.furnitureSvg}>
      <rect x="5" y="8" width="22" height="6" rx="2" fill="currentColor" />
      <rect x="7" y="14" width="3" height="11" rx="1.5" fill="currentColor" />
      <rect x="22" y="14" width="3" height="11" rx="1.5" fill="currentColor" />
      <rect x="12" y="14" width="2" height="8" rx="1" fill="currentColor" opacity="0.5" />
      <rect x="18" y="14" width="2" height="8" rx="1" fill="currentColor" opacity="0.5" />
    </svg>
  )
}

export default function FurnitureCalculator() {
  const [counts, setCounts] = useState<Record<string, number>>({})

  const totalVolume = Object.entries(counts).reduce((sum, [id, count]) => {
    const item = ITEMS.find((entry) => entry.id === id)
    return sum + (item ? item.volume * count : 0)
  }, 0)

  const packedVolume = totalVolume * 1.3
  const requiredArea = packedVolume / 3
  const recommendedBox = BOX_SIZES.find((box) => box.volume >= packedVolume) ?? BOX_SIZES[BOX_SIZES.length - 1]
  const totalItems = Object.values(counts).reduce((sum, count) => sum + count, 0)

  function increment(itemId: string) {
    setCounts((previous) => ({
      ...previous,
      [itemId]: (previous[itemId] ?? 0) + 1,
    }))
  }

  function decrement(itemId: string) {
    setCounts((previous) => {
      const nextCount = (previous[itemId] ?? 0) - 1
      if (nextCount <= 0) {
        const { [itemId]: _removed, ...rest } = previous
        return rest
      }

      return {
        ...previous,
        [itemId]: nextCount,
      }
    })
  }

  function reset() {
    setCounts({})
  }

  return (
    <section className={styles.sectionMuted}>
      <div className="container">
        <div className={styles.sectionHeading}>
          <p className={styles.eyebrow}>Калькулятор</p>
          <h2 className={styles.sectionTitle}>Калькулятор объёма хранения</h2>
          <p className={styles.sectionSubtitle}>
            Отметьте вещи, которые хотите убрать на склад. Мы посчитаем ориентировочный объём и подскажем подходящий
            размер бокса.
          </p>
        </div>

        <div className={styles.calculatorLayout}>
          <div>
            <div className={styles.furnitureGrid}>
              {ITEMS.map((item) => {
                const count = counts[item.id] ?? 0

                return (
                  <div
                    key={item.id}
                    role="button"
                    tabIndex={0}
                    className={`${styles.furnitureCard} ${count > 0 ? styles.furnitureCardActive : ''}`}
                    onClick={() => increment(item.id)}
                    onKeyDown={(event) => {
                      if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault()
                        increment(item.id)
                      }
                    }}
                  >
                    {item.icon === 'table' ? (
                      <TableIcon />
                    ) : (
                      <span className={styles.furnitureEmoji} aria-hidden="true">
                        {item.emoji}
                      </span>
                    )}
                    <span className={styles.furnitureLabel}>{item.label}</span>

                    {count > 0 ? (
                      <span className={styles.countBadge}>
                        <button
                          type="button"
                          className={styles.countButton}
                          onClick={(event) => {
                            event.stopPropagation()
                            decrement(item.id)
                          }}
                          aria-label={`Уменьшить количество: ${item.label}`}
                        >
                          −
                        </button>
                        <span className={styles.countValue}>{count}</span>
                      </span>
                    ) : null}
                  </div>
                )
              })}
            </div>

            {totalItems > 0 ? (
              <button type="button" className={styles.resetButton} onClick={reset}>
                Сбросить расчёт
              </button>
            ) : null}
          </div>

          <aside className={`${styles.card} ${styles.summaryCard}`}>
            <h3 className={styles.summaryTitle}>Ваш склад</h3>
            <p className={styles.summaryText}>Выбирайте вещи слева, а здесь будет расчёт нужного объёма и тарифа.</p>

            <div className={styles.progressBlock}>
              <div className={styles.progressHeader}>
                <span className={styles.muted}>Объём вещей</span>
                <span className={styles.progressValue}>{totalVolume.toFixed(1)} м³</span>
              </div>
              <div className={styles.progressTrack}>
                <div className={styles.progressBar} style={{ width: `${Math.min((packedVolume / 60) * 100, 100)}%` }} />
              </div>
              <div className={styles.progressScale}>
                <span>0 м³</span>
                <span>60 м³</span>
              </div>
            </div>

            {totalVolume === 0 ? (
              <div className={styles.emptyState}>
                <div className={styles.emptyIcon}>📦</div>
                <p>Добавьте предметы, чтобы рассчитать нужный бокс.</p>
              </div>
            ) : (
              <>
                <div className={styles.recommendCard}>
                  <p className={styles.recommendLabel}>Рекомендуем</p>
                  <div className={styles.recommendArea}>{recommendedBox.area} м²</div>
                  <p className={styles.recommendMeta}>{recommendedBox.volume} м³ · высота 3 м</p>
                  <div className={styles.recommendPrice}>
                    <span className={styles.recommendPriceValue}>
                      {recommendedBox.price.toLocaleString('ru-RU')} ₽
                    </span>
                    <span className={styles.recommendPriceSuffix}>/мес</span>
                  </div>
                  <p className={styles.recommendHint}>Ориентир по базовому тарифу, включая НДС.</p>
                </div>

                <div className={styles.stats}>
                  <div className={styles.statRow}>
                    <span>С учётом упаковки</span>
                    <strong>{packedVolume.toFixed(1)} м³</strong>
                  </div>
                  <div className={styles.statRow}>
                    <span>Площадь хранения</span>
                    <strong>≈ {requiredArea.toFixed(1)} м²</strong>
                  </div>
                  <div className={styles.statRow}>
                    <span>Количество предметов</span>
                    <strong>{totalItems} шт.</strong>
                  </div>
                </div>

                <Link href="/online/" className={styles.buttonPrimary}>
                  Перейти к онлайн-аренде
                </Link>
              </>
            )}
          </aside>
        </div>
      </div>
    </section>
  )
}
