import type { Metadata } from 'next'
import { Geologica } from 'next/font/google'
import Link from 'next/link'
import './globals.css'
import './catalog.css'
import './box-detail.css'
import './cabinet.css'

export const metadata: Metadata = {
  title: {
    default: 'Аренда боксов для хранения вещей в Москве — АльфаСклад',
    template: '%s — АльфаСклад',
  },
  description:
    'Боксы от 1 до 76 м² в Москве. Доступ 24/7, охрана, видеонаблюдение. Выберите склад рядом с домом и арендуйте онлайн.',
}

const geologica = Geologica({
  subsets: ['latin', 'cyrillic'],
  weight: ['300', '400', '500', '600'],
  variable: '--font-geologica',
  display: 'swap',
})

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="ru" className={geologica.variable}>
      <body>
        <div className="page-root">
          <SiteHeader />
          {children}
          <SiteFooter />
        </div>
      </body>
    </html>
  )
}

function SiteHeader() {
  return (
    <header className="site-header">
      <div className="container header-inner">
        <Link href="/" className="logo" aria-label="АльфаСклад — главная">
          <span className="logo-alfa">Альфа</span>
          <span className="logo-sklad">Склад</span>
        </Link>

        <nav className="main-nav" aria-label="Навигация">
          <Link href="/" className="nav-link">Склады</Link>
          <a href="https://alfasklad.ru/price/" className="nav-link" target="_blank" rel="noopener">Цены</a>
          <a href="https://alfasklad.ru/services/" className="nav-link" target="_blank" rel="noopener">Услуги</a>
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
          <a href="https://alfasklad.ru/services/dostavka/" className="footer-link" target="_blank" rel="noopener">Доставка</a>
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
