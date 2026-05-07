function withoutTrailingSlash(url: string) {
  return url.replace(/\/+$/, '')
}

function withTrailingSlash(url: string) {
  return `${withoutTrailingSlash(url)}/`
}

export const SITE_URL = withTrailingSlash(
  process.env.NEXT_PUBLIC_SITE_URL ?? 'https://alfasklad.ru/',
)

export const BITRIX_BASE = 'https://alfasklad.ru'
export const CABINET_URL = withTrailingSlash(
  process.env.NEXT_PUBLIC_CABINET_URL ?? `${BITRIX_BASE}/cabinet/`,
)
export const PAYMENT_URL = `${BITRIX_BASE}/payment/`
export const RENTAL_CATALOG_URL = `${BITRIX_BASE}/rental_catalog/`
