'use client'

import { useState } from 'react'
import type { Warehouse } from '@/types/warehouse'

interface Props {
  warehouses: Warehouse[]
  selectedWarehouseId: number | null
  onSelect: (id: number) => void
  onNext: () => void
}

// Собираем уникальные округа из данных БД
function getDistricts(warehouses: Warehouse[]): string[] {
  const set = new Set(warehouses.map(w => w.district).filter(Boolean))
  return ['Все округа', ...Array.from(set)]
}

export default function Step1Warehouse({ warehouses, selectedWarehouseId, onSelect, onNext }: Props) {
  const [activeDistrict, setActiveDistrict] = useState('Все округа')

  const districts = getDistricts(warehouses)

  const filtered = activeDistrict === 'Все округа'
    ? warehouses
    : warehouses.filter(w => w.district === activeDistrict)

  const chip = (active: boolean) => ({
    padding: '6px 14px', borderRadius: 20, fontSize: 13, cursor: 'pointer' as const,
    fontFamily: 'inherit', transition: 'all 0.15s', whiteSpace: 'nowrap' as const,
    border: `1px solid ${active ? '#e8000d' : '#d8d8d8'}`,
    background: active ? '#e8000d' : '#fff',
    color: active ? '#fff' : '#555',
  })

  function handleSelectWarehouse(id: number) {
    onSelect(id)
    onNext()
  }

  return (
    <div>
      <h2 style={{ fontSize: 18, fontWeight: 700, marginBottom: 20 }}>Адреса складов</h2>

      {/* District filter — из реальных данных */}
      {districts.length > 1 && (
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', marginBottom: 20 }}>
          {districts.map(d => (
            <button key={d} style={chip(activeDistrict === d)} onClick={() => setActiveDistrict(d)}>
              {d}
            </button>
          ))}
        </div>
      )}

      <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
        {filtered.map(w => {
          const isSelected = selectedWarehouseId === w.id
          const freeCount  = w.available_boxes_count
          const totalCount = w.total_boxes_count
          const minPrice   = w.price_per_sqm

          return (
            <div
              key={w.id}
              onClick={() => handleSelectWarehouse(w.id)}
              style={{
                border: `1px solid ${isSelected ? '#e8000d' : '#e0e0e0'}`,
                borderRadius: 8, background: isSelected ? '#fff5f5' : '#fff',
                padding: '16px 20px', cursor: 'pointer',
                display: 'flex', justifyContent: 'space-between',
                alignItems: 'center', gap: 16, flexWrap: 'wrap',
                transition: 'all 0.2s',
                boxShadow: isSelected ? '0 0 0 2px rgba(232,0,13,0.15)' : 'none',
              }}
            >
              {/* Left: radio + info */}
              <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                <div style={{
                  width: 20, height: 20, borderRadius: '50%', flexShrink: 0,
                  border: `2px solid ${isSelected ? '#e8000d' : '#ccc'}`,
                  display: 'flex', alignItems: 'center', justifyContent: 'center',
                  transition: 'all 0.2s',
                }}>
                  {isSelected && <div style={{ width: 10, height: 10, borderRadius: '50%', background: '#e8000d' }} />}
                </div>

                <div>
                  <p style={{ fontWeight: 600, fontSize: 14, marginBottom: 4, color: '#333' }}>
                    {w.address || w.name}
                  </p>
                  <div style={{ display: 'flex', gap: 10, alignItems: 'center', flexWrap: 'wrap' }}>
                    {/* Metro stations */}
                    {w.metro.slice(0, 2).map((m, i) => (
                      <span key={i} style={{ fontSize: 12, color: '#555', display: 'flex', alignItems: 'center', gap: 4 }}>
                        <span style={{ width: 8, height: 8, borderRadius: '50%', background: '#e8000d', display: 'inline-block', flexShrink: 0 }} />
                        {m}
                      </span>
                    ))}
                    {/* District badge */}
                    {w.district && (
                      <span style={{ fontSize: 12, color: '#888', background: '#f5f5f5', padding: '2px 8px', borderRadius: 10 }}>
                        {w.district}
                      </span>
                    )}
                    {/* Access hours */}
                    {w.access_hours && (
                      <span style={{ fontSize: 12, color: '#2d9d57', fontWeight: 500 }}>
                        {w.access_hours}
                      </span>
                    )}
                  </div>
                </div>
              </div>

              {/* Right: stats */}
              <div style={{ display: 'flex', gap: 24, alignItems: 'center', flexShrink: 0 }}>
                {/* Available count */}
                <div style={{ textAlign: 'center' }}>
                  <p style={{ fontSize: 11, color: '#888', marginBottom: 2 }}>Свободно</p>
                  <p style={{
                    fontWeight: 700, fontSize: 20, lineHeight: 1,
                    color: freeCount > 10 ? '#2d9d57' : freeCount > 0 ? '#f59e0b' : '#e8000d',
                  }}>
                    {freeCount}
                  </p>
                  <p style={{ fontSize: 11, color: '#888' }}>из {totalCount}</p>
                </div>

                {/* Min price */}
                {minPrice != null && (
                  <div style={{ textAlign: 'right' }}>
                    <p style={{ fontSize: 12, color: '#888', marginBottom: 2 }}>от</p>
                    <p style={{ fontWeight: 700, fontSize: 18, color: '#e8000d', lineHeight: 1 }}>
                      {minPrice.toLocaleString('ru')}
                    </p>
                    <p style={{ fontSize: 12, color: '#888' }}>₽/м²·мес.</p>
                  </div>
                )}
              </div>
            </div>
          )
        })}

        {filtered.length === 0 && (
          <p style={{ color: '#888', padding: '20px 0', textAlign: 'center' }}>
            Нет складов в выбранном округе
          </p>
        )}
      </div>
    </div>
  )
}
