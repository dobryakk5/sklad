'use client'

import Link from 'next/link'
import { usePathname, useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import { adminLogout } from '@/lib/admin-api'
import '../admin.css'

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname()
  const router = useRouter()
  const [name, setName] = useState('')
  const [role, setRole] = useState<string>('')

  useEffect(() => {
    setName(localStorage.getItem('admin_name') ?? '')
    setRole(localStorage.getItem('admin_role') ?? '')
  }, [])

  async function handleLogout() {
    const token = localStorage.getItem('admin_token') ?? ''

    try {
      await adminLogout(token)
    } catch {}

    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_name')
    localStorage.removeItem('admin_role')

    router.push('/admin')
  }

  const isAuthPage = pathname === '/admin'
    || pathname === '/admin/forgot-password'
    || pathname === '/admin/reset-password'

  if (isAuthPage) {
    return <>{children}</>
  }

  return (
    <div className="admin-root">
      <aside className="admin-sidebar">
        <div className="admin-logo">
          <span className="admin-logo-title">АльфаСклад</span>
          <span className="admin-logo-sub">Панель управления</span>
        </div>

        <nav className="admin-nav">
          <Link
            href="/admin/seo"
            className={`admin-nav-link${pathname.startsWith('/admin/seo') ? ' active' : ''}`}
          >
            <span className="admin-nav-icon">🔍</span>
            SEO мета
          </Link>
          <Link
            href="/admin/reviews"
            className={`admin-nav-link${pathname.startsWith('/admin/reviews') ? ' active' : ''}`}
          >
            <span className="admin-nav-icon">⭐</span>
            Отзывы
          </Link>
          {role === 'admin' && (
            <Link
              href="/admin/users"
              className={`admin-nav-link${pathname.startsWith('/admin/users') ? ' active' : ''}`}
            >
              <span className="admin-nav-icon">👤</span>
              Пользователи
            </Link>
          )}
        </nav>

        <div className="admin-nav-bottom">
          {name && (
            <div style={{ padding: '0 12px 4px', fontSize: 12, color: 'rgba(255,255,255,0.35)' }}>
              {name}
            </div>
          )}
          {role && (
            <div style={{ padding: '0 12px 10px', fontSize: 11, color: 'rgba(255,255,255,0.2)' }}>
              {role === 'admin' ? 'Администратор' : 'Оператор'}
            </div>
          )}
          <button className="admin-logout-btn" onClick={handleLogout}>
            <span>↩</span> Выйти
          </button>
        </div>
      </aside>

      <main className="admin-main">
        {children}
      </main>
    </div>
  )
}
