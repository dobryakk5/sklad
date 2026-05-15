'use client'

import { useMemo, useState } from 'react'

type DebtPaymentData = {
  customer_name?: string
  contract_number?: string
  invoice_number?: string
  amount?: string
  currency?: string
  expires_at?: string | null
  payment_url?: string | null
}

export type DebtPaymentState = {
  state: 'ready' | 'payment_created' | 'paid' | 'expired' | 'cancelled' | 'error' | 'not_found'
  message?: string
  data?: DebtPaymentData
}

type Props = {
  token: string
  initialState: DebtPaymentState
}

function getClientApiBase(): string {
  return (process.env.NEXT_PUBLIC_API_URL ?? '/api').replace(/\/+$/, '')
}

function formatMoney(amount?: string, currency = 'RUB') {
  if (!amount) return '0 ₽'

  const [whole, fraction = '00'] = amount.split('.')
  const normalizedWhole = whole.replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
  const symbol = currency === 'RUB' ? '₽' : currency

  return `${normalizedWhole},${fraction.padEnd(2, '0').slice(0, 2)} ${symbol}`
}

function formatDate(value?: string | null) {
  if (!value) return null

  return new Intl.DateTimeFormat('ru-RU', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(value))
}

function titleFor(state: DebtPaymentState['state']) {
  switch (state) {
    case 'paid':
      return 'Счёт уже оплачен'
    case 'expired':
      return 'Срок действия ссылки истёк'
    case 'cancelled':
      return 'Ссылка больше не актуальна'
    case 'not_found':
      return 'Ссылка не найдена'
    case 'error':
      return 'Оплата временно недоступна'
    case 'payment_created':
      return 'Платёж подготовлен'
    default:
      return 'Оплата счёта'
  }
}

export default function PayPageClient({ token, initialState }: Props) {
  const [state, setState] = useState<DebtPaymentState>(initialState)
  const [isLoading, setIsLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const data = state.data
  const expiresAt = useMemo(() => formatDate(data?.expires_at), [data?.expires_at])
  const canPay = state.state === 'ready' || state.state === 'payment_created'
  const buttonLabel = state.state === 'payment_created' ? 'Продолжить оплату' : 'Оплатить'

  async function handlePay() {
    if (state.state === 'payment_created' && data?.payment_url) {
      window.location.href = data.payment_url
      return
    }

    setIsLoading(true)
    setError(null)

    try {
      const response = await fetch(`${getClientApiBase()}/debt-payments/${token}/pay`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
      })
      const payload = await response.json().catch(() => ({}))

      if (!response.ok || !payload.payment_url) {
        throw new Error('payment_failed')
      }

      setState({
        state: 'payment_created',
        data: {
          ...data,
          payment_url: payload.payment_url,
        },
      })
      window.location.href = payload.payment_url
    } catch {
      setError('Не удалось перейти к оплате. Обратитесь в поддержку.')
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <main className="pay-page">
      <section className="pay-panel" aria-labelledby="pay-title">
        <div className="pay-brand">АльфаСклад</div>
        <h1 id="pay-title">{titleFor(state.state)}</h1>

        {data ? (
          <div className="pay-summary">
            <div>
              <span>Клиент</span>
              <strong>{data.customer_name || 'Клиент'}</strong>
            </div>
            <div>
              <span>Договор</span>
              <strong>№{data.contract_number || '-'}</strong>
            </div>
            <div>
              <span>Счёт</span>
              <strong>№{data.invoice_number || '-'}</strong>
            </div>
            <div>
              <span>Сумма</span>
              <strong>{formatMoney(data.amount, data.currency)}</strong>
            </div>
          </div>
        ) : null}

        {expiresAt ? <p className="pay-expiry">Ссылка действует до {expiresAt}</p> : null}

        {state.message ? <p className="pay-message">{state.message}</p> : null}
        {error ? <p className="pay-error">{error}</p> : null}

        {canPay ? (
          <button className="pay-button" type="button" disabled={isLoading} onClick={handlePay}>
            {isLoading ? 'Подготовка оплаты...' : buttonLabel}
          </button>
        ) : (
          <a className="pay-support" href="tel:+74952663974">+7 (495) 266-39-74</a>
        )}
      </section>
    </main>
  )
}
