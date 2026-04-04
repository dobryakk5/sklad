import Link from 'next/link'
import { CabinetIframe } from '@/components/cabinet/CabinetIframe'
import { BITRIX_BASE, CABINET_URL, PAYMENT_URL } from '@/lib/constants'
import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Личный кабинет',
  robots: { index: false, follow: false },
}

const QUICK_LINKS = [
  { label: 'Оплатить аренду',  href: PAYMENT_URL },
  { label: 'Мои договоры',     href: CABINET_URL },
  { label: 'История платежей', href: CABINET_URL },
  { label: 'Связаться с нами', href: 'tel:+74952663974' },
]

export default function CabinetPage() {
  return (
    <main className="cabinet-main">
      <div className="container">

        <nav className="breadcrumbs" aria-label="Навигация">
          <Link href="/" className="bc-link">Главная</Link>
          <span className="bc-sep">›</span>
          <span className="bc-current">Личный кабинет</span>
        </nav>

        <div className="cabinet-layout">

          <aside className="cabinet-sidebar">
            <div className="cabinet-sidebar-card">
              <p className="cabinet-sidebar-title">Личный кабинет</p>
              <p className="cabinet-sidebar-sub">
                Вы просматриваете кабинет alfasklad.ru
              </p>

              <div className="cabinet-divider" />

              <nav aria-label="Быстрые действия">
                <p className="cabinet-nav-label">Быстрые действия</p>
                <ul className="cabinet-nav-list">
                  {QUICK_LINKS.map(({ label, href }) => (
                    <li key={label}>
                      <a
                        href={href}
                        className="cabinet-nav-link"
                        target={href.startsWith('http') ? '_blank' : undefined}
                        rel={href.startsWith('http') ? 'noopener noreferrer' : undefined}
                      >
                        {label}
                        {href.startsWith('http') && (
                          <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                            <path d="M1.5 1.5h8v8M9.5 1.5 L1.5 9.5" stroke="currentColor" strokeWidth="1.3" strokeLinecap="round" />
                          </svg>
                        )}
                      </a>
                    </li>
                  ))}
                </ul>
              </nav>

              <div className="cabinet-divider" />

              <div className="cabinet-note">
                <span className="cabinet-note-icon" aria-hidden="true">ℹ</span>
                <p>
                  Управление договорами и оплата выполняются
                  на&nbsp;сайте&nbsp;alfasklad.ru
                </p>
              </div>
            </div>
          </aside>

          <section className="cabinet-content" aria-label="Личный кабинет">
            <CabinetIframe />
          </section>

        </div>
      </div>
    </main>
  )
}
