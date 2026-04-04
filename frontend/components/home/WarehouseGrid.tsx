'use client'

import { useState, useMemo } from 'react'
import type { Warehouse } from '@/types/warehouse'
import { WarehouseCard } from './WarehouseCard'

const ALL = 'Все'

interface Props {
  warehouses: Warehouse[]
}

export function WarehouseGrid({ warehouses }: Props) {
  const [activeDistrict, setActiveDistrict] = useState(ALL)

  // Собираем уникальные районы, пропускаем пустые строки
  const districts = useMemo(() => {
    const seen = new Set<string>()
    const result: string[] = []
    for (const w of warehouses) {
      if (w.district && !seen.has(w.district)) {
        seen.add(w.district)
        result.push(w.district)
      }
    }
    return result
  }, [warehouses])

  const hasDistricts = districts.length > 0

  const filtered = useMemo(() =>
    activeDistrict === ALL
      ? warehouses
      : warehouses.filter((w) => w.district === activeDistrict),
    [warehouses, activeDistrict]
  )

  return (
    <section className="warehouse-section">
      <div className="section-header">
        <h2 className="section-title">Наши склады</h2>

        {/* Фильтр по районам показываем только если данные есть */}
        {hasDistricts && (
          <div className="district-filters" role="group" aria-label="Фильтр по районам">
            <button
              className={`district-pill${activeDistrict === ALL ? ' active' : ''}`}
              onClick={() => setActiveDistrict(ALL)}
            >
              {ALL}
              <span className="pill-count">{warehouses.length}</span>
            </button>
            {districts.map((d) => {
              const count = warehouses.filter((w) => w.district === d).length
              return (
                <button
                  key={d}
                  className={`district-pill${activeDistrict === d ? ' active' : ''}`}
                  onClick={() => setActiveDistrict(d)}
                >
                  {d}
                  <span className="pill-count">{count}</span>
                </button>
              )
            })}
          </div>
        )}
      </div>

      {filtered.length > 0 ? (
        <div className="warehouse-grid">
          {filtered.map((w, i) => (
            <WarehouseCard key={w.id} warehouse={w} index={i} />
          ))}
        </div>
      ) : (
        <p className="no-results">В этом районе складов пока нет</p>
      )}
    </section>
  )
}
