'use client'

import { useState } from 'react'
import type { Warehouse } from '@/types/warehouse'
import type { Box } from '@/types/box'
import type { RenterFormData } from '@/components/online/steps/Step3Data'

const paymentLogos = [
  { src: 'https://alfasklad.ru/images/logos/mir.svg',        alt: 'Мир'        },
  { src: 'https://alfasklad.ru/images/logos/visa.svg',       alt: 'Visa'       },
  { src: 'https://alfasklad.ru/images/logos/mastercard.svg', alt: 'Mastercard' },
  { src: 'https://alfasklad.ru/images/logos/sbp.svg',        alt: 'СБП'        },
  { src: 'https://alfasklad.ru/images/logos/sber-pay.svg',   alt: 'SberPay'    },
  { src: 'https://alfasklad.ru/images/logos/iomoney.svg',    alt: 'ЮMoney'     },
]

interface Props {
  warehouse: Warehouse
  box: Box
  renter: RenterFormData
  checkoutAttemptId: string
  onPrev: () => void
}

function fmt(d: Date) {
  return d.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

function buildCheckoutComment({
  warehouse,
  box,
  renter,
  checkin,
  endOfMonth,
  rentalCost,
  deposit,
  total,
}: {
  warehouse: Warehouse
  box: Box
  renter: RenterFormData
  checkin: Date
  endOfMonth: Date
  rentalCost: number | null
  deposit: number | null
  total: number
}) {
  const lines = [
    'Онлайн-аренда бокса с сайта A',
    `Склад: ${warehouse.name}${warehouse.address ? `, ${warehouse.address}` : ''}`,
    `Бокс: ${box.object_type || 'Бокс'}${box.box_number ? ` №${box.box_number}` : ''}`,
    `Код 1С: ${box.code_1c || '—'}`,
    `Площадь: ${box.square != null ? `${box.square} м²` : '—'}`,
    `Период оплаты: с ${fmt(checkin)} по ${fmt(endOfMonth)}`,
    `Аренда: ${rentalCost != null ? `${rentalCost.toLocaleString('ru-RU')} руб.` : '—'}`,
    `Депозит: ${deposit != null ? `${deposit.toLocaleString('ru-RU')} руб.` : '—'}`,
    `Итого: ${total.toLocaleString('ru-RU')} руб.`,
  ]

  if (renter.payerType === 'legal') {
    lines.push(
      `Компания: ${renter.companyName || '—'}`,
      `ИНН: ${renter.inn || '—'}`,
      `Контактное лицо: ${renter.contactPerson || '—'}`,
    )
  }

  if (renter.needCall) {
    lines.push('Дополнительно: нужен звонок менеджера')
  }

  if (renter.needDelivery) {
    lines.push('Дополнительно: нужна доставка')
  }

  return lines.join('\n')
}

export default function Step4Payment({ warehouse, box, renter, checkoutAttemptId, onPrev }: Props) {
  const [agreed, setAgreed] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  // Цена: сначала берём price, потом считаем через price_per_sqm * square
  const monthlyPrice = box.price
    ?? (box.price_per_sqm != null && box.square != null
      ? Math.round(box.price_per_sqm * box.square)
      : null)

  // Даты
  const today      = new Date()
  const checkin    = new Date(today); checkin.setDate(today.getDate() + 3)
  const endOfMonth = new Date(checkin.getFullYear(), checkin.getMonth() + 1, 0)
  const daysLeft   = endOfMonth.getDate() - checkin.getDate() + 1

  const rentalCost = monthlyPrice != null
    ? Math.round((monthlyPrice / endOfMonth.getDate()) * daysLeft)
    : null
  const deposit    = monthlyPrice != null ? Math.round(monthlyPrice * 0.5) : null
  const total      = rentalCost != null && deposit != null ? rentalCost + deposit : null

  const checkoutDisabled = !agreed || total == null || loading || !box.code_1c

  async function handleCheckout() {
    if (checkoutDisabled || total == null) {
      return
    }

    try {
      setLoading(true)
      setError(null)

      const res = await fetch('/api/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          externalCheckoutId: checkoutAttemptId,
          customer: {
            email: renter.email,
            phone: renter.phone,
            firstName: renter.payerType === 'individual' ? renter.firstName : '',
            lastName: renter.payerType === 'individual' ? renter.lastName : '',
            middleName: renter.payerType === 'individual' ? renter.middleName : '',
          },
          items: [
            {
              productXmlId: box.code_1c,
              quantity: 1,
              price: total,
            },
          ],
          pricing: {
            grandTotal: total,
          },
          comment: buildCheckoutComment({
            warehouse,
            box,
            renter,
            checkin,
            endOfMonth,
            rentalCost,
            deposit,
            total,
          }),
          savePaymentMethod: false,
        }),
      })

      const data = await res.json().catch(() => null)

      if (!res.ok || !data?.ok || !data?.redirectUrl) {
        throw new Error(data?.error || 'Не удалось перейти к оплате')
      }

      window.location.href = data.redirectUrl
    } catch (checkoutError) {
      console.error(checkoutError)
      setError(
        checkoutError instanceof Error
          ? checkoutError.message
          : 'Не удалось создать платёж. Попробуйте ещё раз.',
      )
    } finally {
      setLoading(false)
    }
  }

  return (
    <div>
      <h2 style={{ fontSize: 18, fontWeight: 700, marginBottom: 24 }}>4. Оплата</h2>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 360px', gap: 24, alignItems: 'start' }} className="online-payment-grid">

        {/* Left: order summary */}
        <div>
          {/* Selected box card */}
          <div style={{ border: '1px solid #e0e0e0', borderRadius: 8, overflow: 'hidden', marginBottom: 16 }}>
            {/* Warehouse */}
            <div style={{ background: '#fafafa', padding: '12px 16px', borderBottom: '1px solid #e8e8e8', display: 'flex', alignItems: 'center', gap: 8 }}>
              <img src="https://alfasklad.ru/images/icons/marker.svg" alt="" style={{ width: 14 }}
                onError={e => { (e.target as HTMLImageElement).style.display = 'none' }} />
              <span style={{ fontSize: 13, fontWeight: 600, color: '#333' }}>
                {warehouse.address || warehouse.name}
              </span>
            </div>

            {/* Box row */}
            <div style={{ padding: '14px 16px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: 12 }}>
              <div>
                <p style={{ fontWeight: 600, fontSize: 14, marginBottom: 6 }}>
                  {box.object_type} {box.square != null ? `${box.square} м²` : ''}
                  {box.volume != null ? `, объём ${box.volume} м³` : ''}
                </p>
                <p style={{ fontSize: 12, color: '#aaa' }}>№{box.box_number || '—'}</p>
              </div>
              <div>
                {monthlyPrice != null ? (
                  <span style={{ fontSize: 14, fontWeight: 600, color: '#e8000d' }}>
                    {monthlyPrice.toLocaleString('ru')} руб. / мес.
                  </span>
                ) : (
                  <span style={{ fontSize: 13, color: '#888' }}>Цена по запросу</span>
                )}
              </div>
            </div>

            {/* Subtotal */}
            <div style={{ background: '#fafafa', padding: '10px 16px', borderTop: '1px solid #e8e8e8', display: 'flex', justifyContent: 'space-between', fontSize: 13, color: '#555' }}>
              <span>Итого: {box.square != null ? `Площадь ${box.square} м²` : ''} · 1 помещение</span>
              {monthlyPrice != null && <strong>{monthlyPrice.toLocaleString('ru')} руб. / мес.</strong>}
            </div>
          </div>

          {/* Dates */}
          <div style={{ border: '1px solid #e0e0e0', borderRadius: 8, padding: '14px 16px', fontSize: 13, color: '#555' }}>
            <div style={{ display: 'flex', gap: 32, flexWrap: 'wrap' }}>
              <div>
                <p style={{ color: '#aaa', marginBottom: 4 }}>Период заезда:</p>
                <p style={{ fontWeight: 600, color: '#333' }}>c {fmt(checkin)}</p>
              </div>
              <div>
                <p style={{ color: '#aaa', marginBottom: 4 }}>Оплачиваемый период:</p>
                <p style={{ fontWeight: 600, color: '#333' }}>c {fmt(checkin)}</p>
              </div>
            </div>
          </div>
        </div>

        {/* Right: payment block */}
        <div style={{ border: '1px solid #e0e0e0', borderRadius: 8, overflow: 'hidden' }}>
          <div style={{ background: '#fafafa', padding: '12px 16px', borderBottom: '1px solid #e8e8e8' }}>
            <p style={{ fontSize: 13, fontWeight: 600, color: '#333' }}>Итого к оплате</p>
            <p style={{ fontSize: 11, color: '#aaa' }}>в том числе:</p>
          </div>
          <div style={{ padding: 16 }}>
            {total != null ? (
              <div style={{ fontSize: 28, fontWeight: 700, color: '#333', marginBottom: 16 }}>
                {total.toLocaleString('ru')} руб.
              </div>
            ) : (
              <div style={{ fontSize: 16, color: '#888', marginBottom: 16 }}>Цена уточняется</div>
            )}

            <div style={{ display: 'flex', flexDirection: 'column', gap: 10, marginBottom: 16, paddingBottom: 16, borderBottom: '1px solid #f0f0f0' }}>
              <div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, color: '#555', marginBottom: 2 }}>
                  <span>Стоимость аренды</span>
                  <strong style={{ color: '#333' }}>
                    {rentalCost != null ? `${rentalCost.toLocaleString('ru')} руб.` : '—'}
                  </strong>
                </div>
                <p style={{ fontSize: 11, color: '#aaa' }}>с {fmt(checkin)} по {fmt(endOfMonth)}</p>
              </div>
              <div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, color: '#555', marginBottom: 2 }}>
                  <span>Гарантийный депозит</span>
                  <strong style={{ color: '#333' }}>
                    {deposit != null ? `${deposit.toLocaleString('ru')} руб.` : '—'}
                  </strong>
                </div>
                <p style={{ fontSize: 11, color: '#aaa' }}>
                  Составляет 50% стоимости месяца аренды.<br />
                  Возвращается при расторжении договора.
                </p>
              </div>
            </div>

            <label style={{ display: 'flex', alignItems: 'flex-start', gap: 10, marginBottom: 14, cursor: 'pointer' }}>
              <input type="checkbox" checked={agreed} onChange={e => setAgreed(e.target.checked)}
                style={{ marginTop: 2, accentColor: '#e8000d', flexShrink: 0 }} />
              <span style={{ fontSize: 12, color: '#555', lineHeight: 1.5 }}>
                Я даю согласие на{' '}
                <a href="https://alfasklad.ru/" style={{ color: '#e8000d', textDecoration: 'none' }}>
                  обработку персональных данных
                </a>
              </span>
            </label>

            <button
              type="button"
              onClick={handleCheckout}
              disabled={checkoutDisabled}
              aria-busy={loading}
              style={{
                width: '100%', padding: 14, border: 'none', borderRadius: 4,
                background: !checkoutDisabled ? '#e8000d' : '#ccc',
                color: '#fff', fontSize: 16, fontWeight: 700,
                fontFamily: 'inherit',
                cursor: !checkoutDisabled ? 'pointer' : 'not-allowed',
                marginBottom: error ? 10 : 12,
              }}
            >
              {loading
                ? 'Перенаправление в оплату...'
                : total != null
                  ? `Оплатить ${total.toLocaleString('ru')} руб.`
                  : 'Оплатить'}
            </button>

            {error && (
              <div
                role="alert"
                style={{
                  borderRadius: 8,
                  border: '1px solid #fecaca',
                  background: '#fef2f2',
                  color: '#991b1b',
                  padding: '12px 14px',
                  fontSize: 13,
                  lineHeight: 1.45,
                  marginBottom: 12,
                }}
              >
                {error}
              </div>
            )}

            <div style={{ display: 'flex', gap: 6, alignItems: 'center', justifyContent: 'center', flexWrap: 'wrap' }}>
              {paymentLogos.map(l => (
                <img key={l.alt} src={l.src} alt={l.alt} style={{ height: 20 }}
                  onError={e => { (e.target as HTMLImageElement).style.display = 'none' }} />
              ))}
            </div>
          </div>
        </div>
      </div>

      <div style={{ marginTop: 24 }}>
        <button onClick={onPrev} style={{ padding: '10px 24px', borderRadius: 4, border: '1px solid #ccc', background: '#fff', color: '#555', fontSize: 14, fontFamily: 'inherit', cursor: 'pointer' }}>
          ← Назад
        </button>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .online-payment-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </div>
  )
}
