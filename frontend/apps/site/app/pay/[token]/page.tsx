import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import PayPageClient, { type DebtPaymentState } from './PayPageClient'
import '../pay.css'

type PageProps = {
  params: Promise<{ token: string }>
}

export const metadata: Metadata = {
  title: 'Оплата счёта',
  description: 'Оплата счёта АльфаСклад',
}

function getApiBase(): string {
  const base =
    process.env.API_BASE_URL ??
    process.env.NEXT_PUBLIC_API_URL ??
    'http://127.0.0.1:8000/api'

  return base.replace(/\/+$/, '')
}

async function getDebtPayment(token: string): Promise<DebtPaymentState> {
  let response: Response

  try {
    response = await fetch(`${getApiBase()}/debt-payments/${token}`, {
      cache: 'no-store',
    })
  } catch {
    return { state: 'error', message: 'Не удалось подготовить оплату. Обратитесь в поддержку.' }
  }

  if (response.status === 404) {
    return { state: 'not_found', message: 'Ссылка не найдена или была удалена.' }
  }

  if (!response.ok) {
    return { state: 'error', message: 'Не удалось подготовить оплату. Обратитесь в поддержку.' }
  }

  return response.json()
}

export default async function DebtPaymentPage({ params }: PageProps) {
  const { token } = await params

  if (!/^[a-f0-9]{32}$/.test(token)) {
    notFound()
  }

  const initialState = await getDebtPayment(token)

  return <PayPageClient token={token} initialState={initialState} />
}
