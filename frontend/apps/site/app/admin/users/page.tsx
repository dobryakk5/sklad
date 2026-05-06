'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import {
  createUser,
  deleteUser,
  getUsersList,
  updateUser,
} from '@/lib/admin-api'
import type { AdminUser } from '@/types/admin'

const EMPTY = { name: '', email: '', password: '', role: 'operator' as 'admin' | 'operator' }

function RoleBadge({ role }: { role: string }) {
  return (
    <span className={`admin-badge ${role === 'admin' ? 'admin-badge-orange' : 'admin-badge-gray'}`}>
      {role === 'admin' ? 'Администратор' : 'Оператор'}
    </span>
  )
}

export default function AdminUsersPage() {
  const router = useRouter()
  const [items, setItems] = useState<AdminUser[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [modalOpen, setModalOpen] = useState(false)
  const [editing, setEditing] = useState<AdminUser | null>(null)
  const [form, setForm] = useState(EMPTY)
  const [saving, setSaving] = useState(false)
  const [formError, setFormError] = useState('')

  function getToken(): string {
    const token = localStorage.getItem('admin_token')
    if (!token) {
      router.push('/admin')
      return ''
    }

    return token
  }

  async function load() {
    const token = getToken()

    if (!token) return

    try {
      const data = await getUsersList(token)
      setItems(data)
    } catch (err: any) {
      setError(err.message)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    const role = localStorage.getItem('admin_role')

    if (role !== 'admin') {
      router.push('/admin/seo')
      return
    }

    load()
  }, [])

  function openCreate() {
    setEditing(null)
    setForm(EMPTY)
    setFormError('')
    setModalOpen(true)
  }

  function openEdit(item: AdminUser) {
    setEditing(item)
    setForm({ name: item.name, email: item.email, password: '', role: item.role })
    setFormError('')
    setModalOpen(true)
  }

  async function handleSave() {
    const token = getToken()

    if (!token) return

    setSaving(true)
    setFormError('')

    try {
      if (editing) {
        const payload: { name: string; email: string; role: 'admin' | 'operator'; password?: string } = {
          name: form.name,
          email: form.email,
          role: form.role,
        }

        if (form.password) {
          payload.password = form.password
        }

        const updated = await updateUser(token, editing.id, payload)
        setItems((prev) => prev.map((item) => item.id === updated.id ? updated : item))
      } else {
        const created = await createUser(token, form)
        setItems((prev) => [...prev, created])
      }

      setModalOpen(false)
    } catch (err: any) {
      setFormError(err.message)
    } finally {
      setSaving(false)
    }
  }

  async function handleDelete(item: AdminUser) {
    if (!confirm(`Удалить пользователя «${item.name}»?`)) return

    const token = getToken()

    if (!token) return

    try {
      await deleteUser(token, item.id)
      setItems((prev) => prev.filter((current) => current.id !== item.id))
    } catch (err: any) {
      alert(err.message)
    }
  }

  function set(field: keyof typeof form, value: string) {
    setForm((prev) => ({ ...prev, [field]: value }))
  }

  return (
    <>
      <div className="admin-toolbar">
        <h1 className="admin-page-title" style={{ margin: 0 }}>Пользователи</h1>
        <button className="btn-admin-primary" onClick={openCreate}>+ Добавить</button>
      </div>

      {error && <div className="admin-error">{error}</div>}

      {loading ? (
        <div className="admin-loading">Загрузка...</div>
      ) : (
        <div className="admin-table-wrap">
          <table className="admin-table">
            <thead>
              <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Добавлен</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {items.length === 0 ? (
                <tr>
                  <td colSpan={5} className="admin-empty">Нет пользователей</td>
                </tr>
              ) : items.map((item) => (
                <tr key={item.id}>
                  <td style={{ fontWeight: 500 }}>{item.name}</td>
                  <td style={{ color: 'var(--gray)', fontSize: 13 }}>{item.email}</td>
                  <td><RoleBadge role={item.role} /></td>
                  <td style={{ color: 'var(--gray-light)', fontSize: 13 }}>
                    {item.created_at
                      ? new Date(item.created_at).toLocaleDateString('ru-RU')
                      : '—'}
                  </td>
                  <td>
                    <div className="admin-table-actions">
                      <button className="btn-admin-secondary" onClick={() => openEdit(item)}>
                        Изменить
                      </button>
                      <button className="btn-admin-danger" onClick={() => handleDelete(item)}>
                        Удалить
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {modalOpen && (
        <div
          className="admin-modal-overlay"
          onClick={(e) => e.target === e.currentTarget && setModalOpen(false)}
        >
          <div className="admin-modal">
            <div className="admin-modal-header">
              <p className="admin-modal-title">
                {editing ? 'Редактировать пользователя' : 'Добавить пользователя'}
              </p>
              <button className="admin-modal-close" onClick={() => setModalOpen(false)}>×</button>
            </div>

            {formError && <div className="admin-error">{formError}</div>}

            <div className="admin-field">
              <label className="admin-label">Имя</label>
              <input
                className="admin-input"
                value={form.name}
                onChange={(e) => set('name', e.target.value)}
                placeholder="Иван Иванов"
              />
            </div>

            <div className="admin-field">
              <label className="admin-label">Email</label>
              <input
                className="admin-input"
                type="email"
                value={form.email}
                onChange={(e) => set('email', e.target.value)}
                placeholder="operator@alfasklad.ru"
              />
            </div>

            <div className="admin-field">
              <label className="admin-label">Роль</label>
              <select
                className="admin-select"
                value={form.role}
                onChange={(e) => set('role', e.target.value)}
              >
                <option value="operator">Оператор</option>
                <option value="admin">Администратор</option>
              </select>
            </div>

            <div className="admin-field">
              <label className="admin-label">
                Пароль
                {editing && (
                  <span style={{ color: 'var(--gray-light)', fontWeight: 400 }}>
                    {' '}— оставьте пустым чтобы не менять
                  </span>
                )}
              </label>
              <input
                className="admin-input"
                type="password"
                value={form.password}
                onChange={(e) => set('password', e.target.value)}
                placeholder={editing ? '••••••••' : 'Минимум 8 символов'}
              />
            </div>

            <div className="admin-modal-footer">
              <button className="btn-admin-secondary" onClick={() => setModalOpen(false)}>
                Отмена
              </button>
              <button className="btn-admin-primary" onClick={handleSave} disabled={saving}>
                {saving ? 'Сохранение...' : 'Сохранить'}
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  )
}
