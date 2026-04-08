'use client'

import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import { adminResetPassword } from '@/lib/admin-api'

export default function ResetPasswordPage() {
  const router = useRouter()
  const [token, setToken] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [confirm, setConfirm] = useState('')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [invalid, setInvalid] = useState(false)

  useEffect(() => {
    const params = new URLSearchParams(window.location.search)
    const nextToken = params.get('token')
    const nextEmail = params.get('email')

    if (!nextToken || !nextEmail) {
      setInvalid(true)
      return
    }

    setToken(nextToken)
    setEmail(nextEmail)
  }, [])

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setError('')

    if (password !== confirm) {
      setError('Пароли не совпадают')
      return
    }

    setLoading(true)

    try {
      await adminResetPassword(token, email, password, confirm)
      router.push('/admin?reset=1')
    } catch (err: any) {
      setError(err.message ?? 'Ошибка сброса пароля')
    } finally {
      setLoading(false)
    }
  }

  if (invalid) {
    return (
      <div className="admin-login-wrap">
        <div className="admin-login-card" style={{ textAlign: 'center' }}>
          <div style={{ fontSize: 40, marginBottom: 16 }}>⚠️</div>
          <p className="admin-login-title">Недействительная ссылка</p>
          <p className="admin-login-sub" style={{ marginBottom: 24 }}>
            Ссылка устарела или некорректна. Запросите новую.
          </p>
          <Link href="/admin/forgot-password" className="btn-admin-primary" style={{ display: 'inline-flex' }}>
            Запросить снова
          </Link>
        </div>
      </div>
    )
  }

  return (
    <div className="admin-login-wrap">
      <div className="admin-login-card">
        <p className="admin-login-title">Новый пароль</p>
        <p className="admin-login-sub">Придумайте новый пароль для входа</p>

        {error && <div className="admin-error">{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="admin-field">
            <label className="admin-label">Новый пароль</label>
            <input
              className="admin-input"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Минимум 8 символов"
              required
              autoFocus
            />
          </div>

          <div className="admin-field">
            <label className="admin-label">Повторите пароль</label>
            <input
              className="admin-input"
              type="password"
              value={confirm}
              onChange={(e) => setConfirm(e.target.value)}
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
            {loading ? 'Сохранение...' : 'Сохранить пароль'}
          </button>
        </form>
      </div>
    </div>
  )
}
