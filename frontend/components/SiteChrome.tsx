'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { getRentalCatalogPath, getRentalModeConfig } from '@/lib/rentalModes'

const serviceLinks = [
  { href: '/services/storage', label: 'Для дома' },
  { href: '/services/business', label: 'Для бизнеса' },
  { href: '/services/delivery', label: 'Доставка' },
]

function normalizePath(path: string) {
  return path !== '/' && path.endsWith('/') ? path.slice(0, -1) : path
}

function matchesPath(pathname: string, href: string) {
  const current = normalizePath(pathname)
  const target = normalizePath(href)

  return current === target || current.startsWith(`${target}/`)
}

export default function SiteChrome({ children }: { children: React.ReactNode }) {
  const pathname = usePathname()

  if (pathname.startsWith('/admin')) {
    return <>{children}</>
  }

  return (
    <div className="page-root">
      <SiteHeader />
      {children}
      <SiteFooter />
    </div>
  )
}

function SiteHeader() {
  const pathname = usePathname()
  const rentalModes = ['box', 'container', 'cell', 'storage', 'room'] as const
  const isServicesActive = matchesPath(pathname, '/services')
  const isRentalActive = matchesPath(pathname, '/rental_catalog')

  return (
    <header className="site-header">
      <div className="container header-inner">
        <Link href="/" className="logo" aria-label="АльфаСклад — главная">
          <span className="logo-alfa">Альфа</span>
          <span className="logo-sklad">Склад</span>
        </Link>

        <nav className="main-nav" aria-label="Навигация">
          <Link href="/" className={`nav-link${pathname === '/' ? ' nav-active' : ''}`}>Склады</Link>
          <div className="nav-dropdown">
            <span className={`nav-link nav-dropdown-trigger${isRentalActive ? ' nav-active' : ''}`}>Аренда</span>
            <div className="nav-dropdown-menu">
              {rentalModes.map((mode) => {
                const config = getRentalModeConfig(mode)
                return (
                  <Link
                    key={mode}
                    href={getRentalCatalogPath(mode)}
                    className={`nav-dropdown-link${matchesPath(pathname, getRentalCatalogPath(mode)) ? ' nav-active' : ''}`}
                  >
                    {config.label}
                  </Link>
                )
              })}
            </div>
          </div>
          <div className="nav-dropdown">
            <Link href="/services" className={`nav-link nav-dropdown-trigger${isServicesActive ? ' nav-active' : ''}`}>Услуги</Link>
            <div className="nav-dropdown-menu">
              {serviceLinks.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className={`nav-dropdown-link${matchesPath(pathname, item.href) ? ' nav-active' : ''}`}
                >
                  {item.label}
                </Link>
              ))}
            </div>
          </div>
          <a href="https://alfasklad.ru/price/" className="nav-link" target="_blank" rel="noopener">Цены</a>
        </nav>

        <div className="header-actions">
          <a href="tel:+74952663974" className="header-phone">+7 (495) 266-39-74</a>
          <Link href="/cabinet" className="btn-outline">Личный кабинет</Link>
        </div>
      </div>
    </header>
  )
}

function SiteFooter() {
  return (
    <footer className="site-footer">
      <div className="container footer-inner">
        <div>
          <div className="logo" style={{ marginBottom: '8px' }}>
            <span className="logo-alfa">Альфа</span>
            <span className="logo-sklad">Склад</span>
          </div>
          <p className="footer-address">Москва, Гостиничный проезд, 6, корп. 2</p>
        </div>
        <div className="footer-links">
          <a href="https://alfasklad.ru/about/" className="footer-link" target="_blank" rel="noopener">О компании</a>
          <a href="https://alfasklad.ru/about/documents/" className="footer-link" target="_blank" rel="noopener">Документы</a>
          <Link href="/services/delivery" className="footer-link">Доставка</Link>
        </div>
        <div className="footer-contact">
          <a href="tel:+74952663974" className="footer-phone">+7 (495) 266-39-74</a>
          <a href="mailto:info@alfasklad.ru" className="footer-email">info@alfasklad.ru</a>
        </div>
      </div>
      <div className="footer-bottom">
        <div className="container">© АльфаСклад {new Date().getFullYear()}</div>
      </div>
    </footer>
  )
}
