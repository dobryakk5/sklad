'use client'

import Link from 'next/link'
import { useState } from 'react'
import { adminForgotPassword } from '@/lib/admin-api'

export default function ForgotPasswordPage() {
  const [email, setEmail] = useState('')
  const [loading, setLoading] = useState(false)
  const [sent, setSent] = useState(false)
  const [error, setError] = useState('')

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setError('')
    setLoading(true)

    try {
      await adminForgotPassword(email)
      setSent(true)
    } catch (err: any) {
      setError(err.message ?? 'Ошибка восстановления пароля')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="admin-login-wrap">
      <div className="admin-login-card">
        <p className="admin-login-title">Восстановление пароля</p>
        <p className="admin-login-sub">Введите email — пришлём ссылку для сброса</p>

        {sent ? (
          <div style={{ textAlign: 'center' }}>
            <div style={{ fontSize: 40, marginBottom: 16 }}>📬</div>
            <p style={{ fontSize: 14, color: 'var(--dark)', marginBottom: 8 }}>
              Если аккаунт с таким email существует, письмо уже отправлено.
            </p>
            <p style={{ fontSize: 13, color: 'var(--gray-light)', marginBottom: 24 }}>
              Проверьте папку «Спам», если письмо не пришло в течение нескольких минут.
            </p>
            <Link href="/admin" className="btn-admin-secondary" style={{ display: 'inline-flex' }}>
              ← Вернуться ко входу
            </Link>
          </div>
        ) : (
          <>
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

              <button
                className="btn-admin-primary"
                type="submit"
                disabled={loading}
                style={{ width: '100%', marginTop: 8 }}
              >
                {loading ? 'Отправка...' : 'Отправить ссылку'}
              </button>
            </form>

            <div style={{ marginTop: 16, textAlign: 'center' }}>
              <Link href="/admin" className="admin-link-muted">
                ← Вернуться ко входу
              </Link>
            </div>
          </>
        )}
      </div>
    </div>
  )
}
