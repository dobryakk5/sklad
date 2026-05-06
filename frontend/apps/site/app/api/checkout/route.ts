import { createHmac, randomUUID } from 'node:crypto'
import { NextRequest, NextResponse } from 'next/server'

type CheckoutItem =
  | {
      invoiceId: number
      quantity?: number
      price?: number
    }
  | {
      productId?: number
      productXmlId?: string
      xmlId?: string
      quantity?: number
      price?: number
      properties?: Record<string, string | number | boolean>
    }

type CheckoutRequestBody = {
  externalCheckoutId?: string
  customer: {
    email: string
    phone: string
    firstName?: string
    lastName?: string
    middleName?: string
    bitrixUserId?: number
  }
  items: CheckoutItem[]
  comment?: string
  savePaymentMethod?: boolean
  pricing?: {
    grandTotal?: number
  }
  siteCode?: string
  personTypeId?: number
  paySystemId?: number
  currency?: string
}

function isPlainObject(value: unknown): value is Record<string, unknown> {
  return typeof value === 'object' && value !== null && !Array.isArray(value)
}

function sortKeysDeep<T>(value: T): T {
  if (Array.isArray(value)) {
    return value.map(sortKeysDeep) as T
  }

  if (!isPlainObject(value)) {
    return value
  }

  const sortedEntries = Object.keys(value)
    .sort()
    .map((key) => [key, sortKeysDeep(value[key])])

  return Object.fromEntries(sortedEntries) as T
}

function canonicalJson(data: unknown): string {
  return JSON.stringify(sortKeysDeep(data))
}

function deriveExternalCheckoutId(body: CheckoutRequestBody): string {
  if (body.externalCheckoutId && body.externalCheckoutId.trim()) {
    return body.externalCheckoutId.trim()
  }

  if (
    body.items.length === 1 &&
    'invoiceId' in body.items[0] &&
    typeof body.items[0].invoiceId === 'number'
  ) {
    return `inv_${body.items[0].invoiceId}`
  }

  throw new Error(
    'Для не-invoice сценария передайте стабильный externalCheckoutId из клиента или сервера',
  )
}

export async function POST(req: NextRequest) {
  try {
    const body = (await req.json()) as CheckoutRequestBody

    if (!body?.customer?.email || !body?.customer?.phone) {
      return NextResponse.json(
        { ok: false, error: 'customer.email и customer.phone обязательны' },
        { status: 400 },
      )
    }

    if (!Array.isArray(body.items) || body.items.length === 0) {
      return NextResponse.json(
        { ok: false, error: 'items должен быть непустым массивом' },
        { status: 400 },
      )
    }

    const bridgeUrl = process.env.BITRIX_BRIDGE_URL
    const sharedSecret = process.env.BITRIX_SHARED_SECRET
    const successUrl = process.env.BITRIX_CHECKOUT_SUCCESS_URL
    const failUrl = process.env.BITRIX_CHECKOUT_FAIL_URL

    if (!bridgeUrl || !sharedSecret || !successUrl || !failUrl) {
      return NextResponse.json(
        { ok: false, error: 'Не заданы env-переменные checkout bridge' },
        { status: 500 },
      )
    }

    const now = Date.now()
    const externalCheckoutId = deriveExternalCheckoutId(body)

    const payload = {
      requestId: `req_${randomUUID()}`,
      externalCheckoutId,
      issuedAt: new Date(now).toISOString(),
      expiresAt: new Date(now + 15 * 60 * 1000).toISOString(),

      siteCode: body.siteCode ?? 's1',
      personTypeId: body.personTypeId ?? 1,
      paySystemId: body.paySystemId ?? 7,
      currency: body.currency ?? 'RUB',

      customer: {
        email: body.customer.email,
        phone: body.customer.phone,
        firstName: body.customer.firstName ?? '',
        lastName: body.customer.lastName ?? '',
        middleName: body.customer.middleName ?? '',
        ...(typeof body.customer.bitrixUserId === 'number'
          ? { bitrixUserId: body.customer.bitrixUserId }
          : {}),
      },

      items: body.items.map((item) => {
        if ('invoiceId' in item) {
          return {
            invoiceId: item.invoiceId,
            quantity: item.quantity ?? 1,
            ...(typeof item.price === 'number' ? { price: item.price } : {}),
          }
        }

        return {
          ...(typeof item.productId === 'number' ? { productId: item.productId } : {}),
          ...(item.productXmlId ? { productXmlId: item.productXmlId } : {}),
          ...(item.xmlId ? { xmlId: item.xmlId } : {}),
          quantity: item.quantity ?? 1,
          ...(typeof item.price === 'number' ? { price: item.price } : {}),
          ...(item.properties ? { properties: item.properties } : {}),
        }
      }),

      ...(body.comment ? { comment: body.comment } : {}),
      ...(body.savePaymentMethod ? { savePaymentMethod: true } : {}),
      ...(typeof body.pricing?.grandTotal === 'number'
        ? { pricing: { grandTotal: body.pricing.grandTotal } }
        : {}),

      redirect: {
        successUrl,
        failUrl,
      },
    }

    const signature = createHmac('sha256', sharedSecret)
      .update(canonicalJson(payload))
      .digest('base64')

    const bridgeResponse = await fetch(bridgeUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Signature': signature,
      },
      body: JSON.stringify(payload),
      cache: 'no-store',
    })

    const bridgeData = await bridgeResponse.json().catch(() => null)

    if (!bridgeResponse.ok || !bridgeData?.ok) {
      return NextResponse.json(
        {
          ok: false,
          error: bridgeData?.error ?? 'Bitrix bridge error',
          bridgeStatus: bridgeResponse.status,
        },
        { status: 502 },
      )
    }

    return NextResponse.json({
      ok: true,
      redirectUrl: bridgeData.redirectUrl,
      orderId: bridgeData.orderId,
      paymentId: bridgeData.paymentId,
      paymentInvoiceId: bridgeData.paymentInvoiceId,
      externalCheckoutId: bridgeData.externalCheckoutId,
      status: bridgeData.status,
    })
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Unknown checkout error'

    return NextResponse.json({ ok: false, error: message }, { status: 500 })
  }
}
