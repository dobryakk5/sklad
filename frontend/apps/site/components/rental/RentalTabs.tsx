'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { getRentalCatalogPath } from '@/lib/rentalModes'
import type { RentalMode } from '@/types/rental'
import styles from './RentalLanding.module.css'

const tabs: Array<{ mode: RentalMode; label: string }> = [
  { mode: 'box', label: 'Аренда бокса' },
  { mode: 'container', label: 'Контейнеры' },
  { mode: 'cell', label: 'Ячейки' },
  { mode: 'storage', label: 'Кладовки' },
  { mode: 'room', label: 'Помещения' },
]

function normalizePath(path: string) {
  return path !== '/' && path.endsWith('/') ? path.slice(0, -1) : path
}

export function RentalTabs() {
  const pathname = usePathname()
  const current = normalizePath(pathname)

  return (
    <nav className={styles.tabs} aria-label="Разделы аренды">
      {tabs.map((tab) => {
        const href = getRentalCatalogPath(tab.mode)
        const isActive = current === normalizePath(href)

        return (
          <Link
            key={tab.mode}
            href={href}
            className={`${styles.tab} ${isActive ? styles.tabActive : ''}`}
          >
            {tab.label}
          </Link>
        )
      })}
    </nav>
  )
}
