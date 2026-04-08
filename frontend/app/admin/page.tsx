'use client'

import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import { adminLogin } from '@/lib/admin-api'

export default function AdminLoginPage() {
  const router = useRouter()
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [notice, setNotice] = useState('')
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    const params = new URLSearchParams(window.location.search)

    if (params.get('reset') === '1') {
      setNotice('Пароль обновлён. Теперь войдите с новым паролем.')
    }
  }, [])

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setError('')
    setLoading(true)

    try {
      const { token, name, role } = await adminLogin(email, password)
      localStorage.setItem('admin_token', token)
      localStorage.setItem('admin_name', name)
      localStorage.setItem('admin_role', role)
      router.push('/admin/seo')
    } catch (err: any) {
      setError(err.message ?? 'Ошибка авторизации')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="admin-login-wrap">
      <div className="admin-login-card">
        <p className="admin-login-title">Вход в панель</p>
        <p className="admin-login-sub">АльфаСклад — управление сайтом</p>

        {notice && (
          <div
            style={{
              padding: '10px 14px',
              background: '#edf7ed',
              border: '1px solid #cde7ce',
              borderRadius: 'var(--radius-sm)',
              fontSize: 13,
              color: '#256a2d',
              marginBottom: 16,
            }}
          >
            {notice}
          </div>
        )}
        {error && <div className="admin-error">{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="admin-field">
            <label className="admin-label">Email</label>
            <input
              className="admin-input"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="operator@alfasklad.ru"
              required
              autoFocus
            />
          </div>

          <div className="admin-field">
            <label className="admin-label">Пароль</label>
            <input
              className="admin-input"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="••••••••"
              required
            />
          </div>

          <button
            className="btn-admin-primary"
            type="submit"
            disabled={loading}
            style={{ width: '100%', marginTop: 8 }}
          >
            {loading ? 'Вход...' : 'Войти'}
          </button>
        </form>

        <div style={{ marginTop: 16, textAlign: 'center' }}>
          <Link href="/admin/forgot-password" className="admin-link-muted">
            Забыли пароль?
          </Link>
        </div>
      </div>
    </div>
  )
}
