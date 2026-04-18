'use client'

import { useState } from 'react'
import type { Box, BoxFloor } from '@/types/box'

const AREA_RANGES = [
  { label: '1–1,75 м²', min: 1, max: 1.75 },
  { label: '2–3 м²', min: 2, max: 3 },
  { label: '3,5–5,5 м²', min: 3.5, max: 5.5 },
  { label: '6–9,5 м²', min: 6, max: 9.5 },
  { label: '10–14,5 м²', min: 10, max: 14.5 },
  { label: '15–29,5 м²', min: 15, max: 29.5 },
  { label: 'от 30 м²', min: 30, max: 9999 },
]

const ROOM_TYPES = ['Бокс', 'Ячейка', 'Контейнер'] as const

const CELL_POSITION_OPTIONS = [
  { id: 'level-1-left', label: '1 ярус', level: 1 as const },
  { id: 'level-1-right', label: '1 ярус', level: 1 as const },
  { id: 'level-2-left', label: '2 ярус', level: 2 as const },
  { id: 'level-2-right', label: '2 ярус', level: 2 as const },
]

function getDisplayType(box: Box): string {
  const objectType = box.object_type?.trim()
  const rentType = box.rent_type?.trim().toLowerCase()

  if (objectType === 'Антресольный бокс') return 'Бокс'
  if (objectType === 'Бокс' || objectType === 'Ячейка' || objectType === 'Контейнер') return objectType

  if (rentType?.includes('ячейк')) return 'Ячейка'
  if (rentType?.includes('контейнер')) return 'Контейнер'
  if (rentType?.includes('бокс')) return 'Бокс'

  return 'Бокс'
}

function getTypes(boxes: Box[]): string[] {
  const found = new Set(boxes.map(getDisplayType).filter(Boolean))
  return ROOM_TYPES.filter(type => found.has(type))
}

function floorLabel(floor: BoxFloor): string {
  if (floor === 'first') return '1'
  if (floor === 'second') return '2'
  if (floor === 'third') return '3'
  return '—'
}

function getPlanImage(box: Box): { levelImg: string; planImg: string } {
  const base = 'https://alfasklad.ru/images/plans'
  const sq = box.square ?? 0
  const type = getDisplayType(box)

  if (type === 'Ячейка') {
    return {
      levelImg: `${base}/level-1.svg`,
      planImg: `${base}/cell.svg`,
    }
  }

  if (type === 'Контейнер') {
    if (sq <= 2) return { levelImg: `${base}/container-XS-long.svg`, planImg: `${base}/container-XS-long.svg` }
    if (sq <= 5) return { levelImg: `${base}/container-S-square.svg`, planImg: `${base}/container-S-square.svg` }
    return { levelImg: `${base}/container-M-long.svg`, planImg: `${base}/container-M-long.svg` }
  }

  const levelImg = box.floor === 'first' ? `${base}/door.svg` : `${base}/level-2.svg`
  if (sq <= 1.75) return { levelImg, planImg: `${base}/box-XS.svg` }
  if (sq <= 3) return { levelImg, planImg: `${base}/box-S-square.svg` }
  if (sq <= 5.5) return { levelImg, planImg: `${base}/box-S-long.svg` }
  if (sq <= 9.5) return { levelImg, planImg: `${base}/box-M-square.svg` }
  if (sq <= 14.5) return { levelImg, planImg: `${base}/box-L-square.svg` }
  return { levelImg, planImg: `${base}/box-L-long.svg` }
}

function getCardCopy(box: Box): { title: string; subtitle: string | null } {
  const type = getDisplayType(box)
  const square = box.square != null ? `${box.square} м²` : null
  const volume = box.volume != null ? `${box.volume} м³` : null

  if (type === 'Ячейка') {
    return {
      title: `${type} ${volume ?? ''}`.trim(),
      subtitle: square ? `Площадь ${square}` : null,
    }
  }

  return {
    title: `${type} ${square ?? ''}`.trim(),
    subtitle: volume ? `Объем ${volume}` : null,
  }
}

function CellPositionIcon({ level }: { level: 1 | 2 }) {
  if (level === 1) {
    return (
      <svg width="69" height="99" viewBox="0 0 69 99" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect x="33.5" y="12.5" width="23" height="23" rx="1.5" fill="white" />
        <path d="M41.0771 30V29.184L45.7331 24.624C46.1598 24.208 46.4798 23.8453 46.6931 23.536C46.9065 23.216 47.0505 22.9173 47.1251 22.64C47.1998 22.3627 47.2371 22.1013 47.2371 21.856C47.2371 21.2053 47.0131 20.6933 46.5651 20.32C46.1278 19.9467 45.4771 19.76 44.6131 19.76C43.9518 19.76 43.3651 19.8613 42.8531 20.064C42.3518 20.2667 41.9198 20.5813 41.5571 21.008L40.7411 20.304C41.1785 19.792 41.7385 19.3973 42.4211 19.12C43.1038 18.8427 43.8665 18.704 44.7091 18.704C45.4665 18.704 46.1225 18.8267 46.6771 19.072C47.2318 19.3067 47.6585 19.6533 47.9571 20.112C48.2665 20.5707 48.4211 21.1093 48.4211 21.728C48.4211 22.0907 48.3678 22.448 48.2611 22.8C48.1651 23.152 47.9838 23.5253 47.7171 23.92C47.4611 24.304 47.0825 24.7413 46.5811 25.232L42.3091 29.424L41.9891 28.976L48.9331 28.976V30H41.0771Z" fill="#EF5A54" />
        <rect x="33.5" y="37.5" width="23" height="23" rx="1.5" stroke="#EF5A54" />
        <path d="M44.8934 55V44.28L45.4054 44.824H42.2534V43.8H46.0454V55H44.8934Z" fill="white" />
        <path d="M20 43.8429V57.7714M20 57.7714C20 59.0022 18.9767 60 17.7143 60C16.4519 60 15.4286 59.0022 15.4286 57.7714L15.4286 36.8786M20 57.7714C20 59.0022 21.0233 60 22.2857 60C23.5481 60 24.5714 59.0022 24.5714 57.7714L24.5714 36.8786M15.4286 46.0714C15.4286 46.9945 14.6611 47.7429 13.7143 47.7429C12.7675 47.7429 12 46.9945 12 46.0714L12 36.0429C12 33.2735 14.3025 31.0286 17.1429 31.0286H22.8571C25.6975 31.0286 28 33.2735 28 36.0429L28 46.0714C28 46.9945 27.2325 47.7429 26.2857 47.7429C25.3389 47.7429 24.5714 46.9945 24.5714 46.0714M24 25.1786C24 27.4863 22.0812 29.3571 19.7143 29.3571C17.3474 29.3571 15.4286 27.4863 15.4286 25.1786C15.4286 22.8708 17.3474 21 19.7143 21C22.0812 21 24 22.8708 24 25.1786Z" stroke="#EF5A54" strokeLinecap="round" />
      </svg>
    )
  }

  return (
    <svg width="69" height="99" viewBox="0 0 69 99" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <rect x="33.5" y="12.5" width="23" height="23" rx="1.5" fill="#EF5A54" />
      <path d="M41.0771 30V29.184L45.7331 24.624C46.1598 24.208 46.4798 23.8453 46.6931 23.536C46.9065 23.216 47.0505 22.9173 47.1251 22.64C47.1998 22.3627 47.2371 22.1013 47.2371 21.856C47.2371 21.2053 47.0131 20.6933 46.5651 20.32C46.1278 19.9467 45.4771 19.76 44.6131 19.76C43.9518 19.76 43.3651 19.8613 42.8531 20.064C42.3518 20.2667 41.9198 20.5813 41.5571 21.008L40.7411 20.304C41.1785 19.792 41.7385 19.3973 42.4211 19.12C43.1038 18.8427 43.8665 18.704 44.7091 18.704C45.4665 18.704 46.1225 18.8267 46.6771 19.072C47.2318 19.3067 47.6585 19.6533 47.9571 20.112C48.2665 20.5707 48.4211 21.1093 48.4211 21.728C48.4211 22.0907 48.3678 22.448 48.2611 22.8C48.1651 23.152 47.9838 23.5253 47.7171 23.92C47.4611 24.304 47.0825 24.7413 46.5811 25.232L42.3091 29.424L41.9891 28.976H48.9331V30L41.0771 30Z" fill="white" />
      <rect x="33.5" y="37.5" width="23" height="23" rx="1.5" stroke="#EF5A54" />
      <path d="M44.8934 55V44.28L45.4054 44.824H42.2534V43.8H46.0454V55H44.8934Z" fill="#EF5A54" />
      <path d="M20 43.8429L20 57.7714M20 57.7714C20 59.0022 18.9767 60 17.7143 60C16.4519 60 15.4286 59.0022 15.4286 57.7714L15.4286 36.8786M20 57.7714C20 59.0022 21.0233 60 22.2857 60C23.5481 60 24.5714 59.0022 24.5714 57.7714L24.5714 36.8786M15.4286 46.0714C15.4286 46.9945 14.6611 47.7429 13.7143 47.7429C12.7675 47.7429 12 46.9945 12 46.0714L12 36.0429C12 33.2735 14.3025 31.0286 17.1429 31.0286H22.8571C25.6975 31.0286 28 33.2735 28 36.0429V46.0714C28 46.9945 27.2325 47.7429 26.2857 47.7429C25.3389 47.7429 24.5714 46.9945 24.5714 46.0714M24 25.1786C24 27.4863 22.0812 29.3571 19.7143 29.3571C17.3474 29.3571 15.4286 27.4863 15.4286 25.1786C15.4286 22.8708 17.3474 21 19.7143 21C22.0812 21 24 22.8708 24 25.1786Z" stroke="#EF5A54" strokeLinecap="round" />
    </svg>
  )
}

interface Props {
  boxes: Box[]
  loading: boolean
  error: string | null
  cart: Record<number, number>
  onCartChange: (id: number, qty: number) => void
  onNext: () => void
  onPrev: () => void
}

export default function Step2Room({
  boxes,
  loading,
  error,
  cart,
  onCartChange,
  onNext,
  onPrev,
}: Props) {
  const [typeFilter, setTypeFilter] = useState<string | null>(null)
  const [areaFilter, setAreaFilter] = useState<typeof AREA_RANGES[0] | null>(null)
  const [floorFilter, setFloorFilter] = useState<string | null>(null)
  const [showAll, setShowAll] = useState(false)

  const types = getTypes(boxes)
  const hasFirstFloor = boxes.some(box => box.floor === 'first')
  const hasUpperFloors = boxes.some(box => box.floor !== 'first')

  const boxesForAreaAvailability = boxes.filter(box => {
    if (typeFilter && getDisplayType(box) !== typeFilter) return false
    if (floorFilter === '1 этаж' && box.floor !== 'first') return false
    if (floorFilter === '2–N этаж' && box.floor === 'first') return false
    return true
  })

  const filtered = boxes.filter(box => {
    if (typeFilter && getDisplayType(box) !== typeFilter) return false

    if (areaFilter && box.square != null) {
      if (box.square < areaFilter.min || box.square > areaFilter.max) return false
    }

    if (floorFilter === '1 этаж' && box.floor !== 'first') return false
    if (floorFilter === '2–N этаж' && box.floor === 'first') return false

    return true
  })

  const displayed = showAll ? filtered : filtered.slice(0, 6)
  const cartHasItems = Object.values(cart).some(qty => qty > 0)

  const filterChip = (active: boolean, wide = false) => ({
    padding: wide ? '10px 14px' : '8px 16px',
    border: `1px solid ${active ? '#e8000d' : '#e0e0e0'}`,
    background: active ? '#e8000d' : '#fff',
    color: active ? '#fff' : '#333',
    borderRadius: wide ? 6 : 999,
    fontSize: 13,
    fontWeight: active ? 600 : 400,
    cursor: 'pointer' as const,
    fontFamily: 'inherit',
    transition: 'all 0.15s',
    textAlign: 'center' as const,
  })

  const disabledFilterChip = (wide = false) => ({
    ...filterChip(false, wide),
    background: '#fafafa',
    color: '#a8a8a8',
    border: '1px solid #ececec',
    cursor: 'not-allowed' as const,
  })

  const disabledCellFilterChip = {
    padding: '10px 8px',
    border: '1px solid #e4e4e4',
    background: '#fafafa',
    borderRadius: 8,
    cursor: 'not-allowed' as const,
    display: 'flex',
    flexDirection: 'column' as const,
    alignItems: 'center' as const,
    gap: 6,
    opacity: 0.55,
  }

  const sectionTitle = (accent: string, tail?: string) => (
    <p style={{ fontSize: 14, marginBottom: 12 }}>
      <strong>{accent}</strong>{tail ? ` ${tail}` : ''}
    </p>
  )

  return (
    <div>
      <h2 style={{ fontSize: 18, fontWeight: 700, marginBottom: 20 }}>2. Выбор помещения</h2>

      {loading && (
        <div style={{ padding: '40px 0', textAlign: 'center', color: '#888' }}>
          <div style={{ width: 36, height: 36, border: '3px solid #f0f0f0', borderTopColor: '#e8000d', borderRadius: '50%', margin: '0 auto 12px', animation: 'spin 0.8s linear infinite' }} />
          Загружаем доступные помещения…
          <style>{`@keyframes spin{to{transform:rotate(360deg)}}`}</style>
        </div>
      )}

      {error && !loading && (
        <div style={{ padding: 16, background: '#fff5f5', border: '1px solid #fca5a5', borderRadius: 8, color: '#b91c1c', marginBottom: 20 }}>
          {error}
        </div>
      )}

      {!loading && !error && (
        <div style={{ display: 'grid', gridTemplateColumns: '260px 1fr', gap: 28, alignItems: 'start' }} className="step2-grid">
          <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
            <div>
              {sectionTitle('Тип', 'помещения')}
              <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {ROOM_TYPES.map(type => {
                  const available = types.includes(type)

                  return (
                    <button
                      key={type}
                      disabled={!available}
                      style={available ? filterChip(typeFilter === type) : disabledFilterChip()}
                      onClick={() => {
                        if (!available) return
                        setTypeFilter(typeFilter === type ? null : type)
                        setShowAll(false)
                      }}
                    >
                      {type}
                    </button>
                  )
                })}
              </div>
            </div>

            <div>
              {sectionTitle('Расположение', 'ячейки')}
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8 }}>
                {CELL_POSITION_OPTIONS.map(option => (
                  <button key={option.id} type="button" disabled style={disabledCellFilterChip}>
                    <CellPositionIcon level={option.level} />
                    <span style={{ fontSize: 12, color: '#666', fontFamily: 'inherit' }}>
                      {option.label}
                    </span>
                  </button>
                ))}
              </div>
            </div>

            <div>
              {sectionTitle('Площадь', 'помещения')}
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8 }}>
                {AREA_RANGES.map(area => {
                  const available = boxesForAreaAvailability.some(box => {
                    if (box.square == null) return false
                    return box.square >= area.min && box.square <= area.max
                  })

                  return (
                    <button
                      key={area.label}
                      disabled={!available}
                      style={available ? filterChip(areaFilter?.label === area.label, true) : disabledFilterChip(true)}
                      onClick={() => {
                        if (!available) return
                        setAreaFilter(areaFilter?.label === area.label ? null : area)
                        setShowAll(false)
                      }}
                    >
                      {area.label}
                    </button>
                  )
                })}
              </div>
            </div>

            <div>
              {sectionTitle('Этаж')}
              <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {[
                  { label: '1 этаж', available: hasFirstFloor },
                  { label: '2–N этаж', available: hasUpperFloors },
                ].map(option => (
                  <button
                    key={option.label}
                    disabled={!option.available}
                    style={option.available ? filterChip(floorFilter === option.label) : disabledFilterChip()}
                    onClick={() => {
                      if (!option.available) return
                      setFloorFilter(floorFilter === option.label ? null : option.label)
                      setShowAll(false)
                    }}
                  >
                    {option.label}
                  </button>
                ))}
              </div>
            </div>
          </div>

          <div>
            <p style={{ fontSize: 14, color: '#555', marginBottom: 16 }}>
              Найдено: <strong>{filtered.length}</strong>{' '}
              {filtered.length === 1 ? 'помещение' : filtered.length < 5 ? 'помещения' : 'помещений'}
            </p>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }} className="cards-grid">
              {displayed.map(box => {
                const qty = cart[box.id] ?? 0
                const isInCart = qty > 0
                const { levelImg, planImg } = getPlanImage(box)
                const { title, subtitle } = getCardCopy(box)
                const price = box.price ?? (
                  box.price_per_sqm != null && box.square != null
                    ? Math.round(box.price_per_sqm * box.square)
                    : null
                )

                return (
                  <div
                    key={box.id}
                    style={{
                      border: `1px solid ${isInCart ? '#e8000d' : '#e0e0e0'}`,
                      borderRadius: 8,
                      background: '#fff',
                      overflow: 'hidden',
                      boxShadow: isInCart ? '0 0 0 2px rgba(232,0,13,0.15)' : 'none',
                      transition: 'all 0.2s',
                      display: 'flex',
                      flexDirection: 'column',
                    }}
                  >
                    <div style={{ padding: '14px 16px 10px', textAlign: 'center', borderBottom: '1px solid #f0f0f0' }}>
                      <p style={{ fontSize: 15, marginBottom: 2 }}>{title}</p>
                      {subtitle && (
                        <p style={{ fontSize: 12, color: '#888' }}>{subtitle}</p>
                      )}
                    </div>

                    <div style={{ padding: '12px 16px', display: 'flex', gap: 8, alignItems: 'flex-end', justifyContent: 'space-between', minHeight: 110, borderBottom: '1px solid #f0f0f0' }}>
                      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-start', gap: 2 }}>
                        <span style={{ fontSize: 20, fontWeight: 700, color: '#333', lineHeight: 1 }}>{floorLabel(box.floor)}</span>
                        <span style={{ fontSize: 11, color: '#888' }}>этаж</span>
                      </div>

                      <div style={{ display: 'flex', gap: 6, alignItems: 'flex-end', flex: 1, justifyContent: 'center' }}>
                        <img
                          src={planImg}
                          alt="план помещения"
                          style={{ maxHeight: 90, maxWidth: '60%', objectFit: 'contain' }}
                          onError={event => { (event.target as HTMLImageElement).style.display = 'none' }}
                        />
                      </div>
                    </div>

                    <div style={{ padding: '8px 16px', borderBottom: '1px solid #f0f0f0', display: 'flex', alignItems: 'center', gap: 8 }}>
                      <img
                        src={levelImg}
                        alt="схема доступа"
                        style={{ height: 32, width: 'auto', opacity: 0.72 }}
                        onError={event => { (event.target as HTMLImageElement).style.display = 'none' }}
                      />
                      {box.box_number && (
                        <span style={{ fontSize: 11, color: '#888' }}>№{box.box_number}</span>
                      )}
                    </div>

                    <div style={{ padding: '12px 16px', textAlign: 'center', borderBottom: '1px solid #f0f0f0' }}>
                      {price != null ? (
                        <span style={{ fontSize: 18, fontWeight: 700, color: '#222' }}>
                          {price.toLocaleString('ru')} <span style={{ fontWeight: 400 }}>руб. / мес.</span>
                        </span>
                      ) : (
                        <span style={{ fontSize: 14, color: '#888' }}>Цена по запросу</span>
                      )}
                    </div>

                    <div style={{ padding: '12px 16px' }}>
                      {isInCart ? (
                        <div style={{
                          display: 'flex',
                          alignItems: 'center',
                          border: '1px solid #e8000d',
                          borderRadius: 6,
                          overflow: 'hidden',
                        }}>
                          <button
                            type="button"
                            onClick={() => onCartChange(box.id, qty - 1)}
                            style={{
                              width: 38,
                              height: 38,
                              border: 'none',
                              background: 'transparent',
                              color: '#e8000d',
                              fontSize: 22,
                              lineHeight: 1,
                              cursor: 'pointer',
                              display: 'flex',
                              alignItems: 'center',
                              justifyContent: 'center',
                              flexShrink: 0,
                            }}
                          >
                            −
                          </button>
                          <span style={{
                            flex: 1,
                            textAlign: 'center',
                            fontSize: 14,
                            fontWeight: 600,
                            color: '#222',
                          }}>
                            {qty}
                          </span>
                          <button
                            type="button"
                            onClick={() => onCartChange(box.id, qty + 1)}
                            style={{
                              width: 38,
                              height: 38,
                              border: 'none',
                              background: 'transparent',
                              color: '#e8000d',
                              fontSize: 22,
                              lineHeight: 1,
                              cursor: 'pointer',
                              display: 'flex',
                              alignItems: 'center',
                              justifyContent: 'center',
                              flexShrink: 0,
                            }}
                          >
                            +
                          </button>
                        </div>
                      ) : (
                        <button
                          type="button"
                          onClick={() => onCartChange(box.id, 1)}
                          style={{
                            width: '100%',
                            padding: '10px',
                            borderRadius: 6,
                            border: '1px solid #e8000d',
                            background: 'transparent',
                            color: '#e8000d',
                            fontSize: 14,
                            fontWeight: 600,
                            fontFamily: 'inherit',
                            cursor: 'pointer',
                            transition: 'all 0.15s',
                          }}
                        >
                          Выбрать
                        </button>
                      )}
                    </div>
                  </div>
                )
              })}
            </div>

            {filtered.length === 0 && boxes.length > 0 && (
              <div style={{ padding: '32px 0', textAlign: 'center', color: '#888' }}>
                <p style={{ marginBottom: 12 }}>Нет помещений по выбранным фильтрам.</p>
                <button
                  onClick={() => {
                    setTypeFilter(null)
                    setAreaFilter(null)
                    setFloorFilter(null)
                  }}
                  style={{
                    background: 'none',
                    border: '1px solid #e8000d',
                    color: '#e8000d',
                    padding: '8px 20px',
                    borderRadius: 6,
                    fontSize: 13,
                    cursor: 'pointer',
                    fontFamily: 'inherit',
                  }}
                >
                  Сбросить фильтры
                </button>
              </div>
            )}

            {filtered.length > 6 && (
              <div style={{ marginTop: 16, textAlign: 'center' }}>
                <button
                  onClick={() => setShowAll(!showAll)}
                  style={{
                    background: 'none',
                    border: '1px solid #e8000d',
                    color: '#e8000d',
                    padding: '9px 24px',
                    borderRadius: 6,
                    fontSize: 14,
                    cursor: 'pointer',
                    fontFamily: 'inherit',
                    fontWeight: 500,
                  }}
                >
                  {showAll ? 'Скрыть' : `Показать ещё (${filtered.length - 6})`}
                </button>
              </div>
            )}
          </div>
        </div>
      )}

      <div style={{ marginTop: 28, display: 'flex', justifyContent: 'space-between' }}>
        <button
          onClick={onPrev}
          style={{
            padding: '10px 24px',
            borderRadius: 6,
            border: '1px solid #ccc',
            background: '#fff',
            color: '#555',
            fontSize: 14,
            fontFamily: 'inherit',
            cursor: 'pointer',
          }}
        >
          ← Назад
        </button>

        <button
          onClick={onNext}
          disabled={!cartHasItems}
          style={{
            padding: '10px 28px',
            borderRadius: 6,
            border: 'none',
            fontSize: 14,
            fontFamily: 'inherit',
            fontWeight: 600,
            background: cartHasItems ? '#e8000d' : '#ccc',
            color: '#fff',
            cursor: cartHasItems ? 'pointer' : 'not-allowed',
          }}
        >
          Ввести данные →
        </button>
      </div>

      <style>{`
        @media (max-width: 900px) {
          .step2-grid { grid-template-columns: 1fr !important; }
          .cards-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 560px) {
          .cards-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </div>
  )
}
