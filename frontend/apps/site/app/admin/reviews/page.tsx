'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import {
  createReview,
  deleteReview,
  getReviewsList,
  updateReview,
} from '@/lib/admin-api'
import type { Review } from '@/types/admin'

const EMPTY: Omit<Review, 'id'> = {
  author_name: '',
  text: '',
  rating: 5,
  date: new Date().toISOString().slice(0, 10),
  photo_url: '',
  source_url: '',
  is_active: true,
}

function Stars({ rating }: { rating: number }) {
  return (
    <span className="admin-stars">
      {'★'.repeat(rating)}{'☆'.repeat(5 - rating)}
    </span>
  )
}

export default function AdminReviewsPage() {
  const router = useRouter()
  const [items, setItems] = useState<Review[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [modalOpen, setModalOpen] = useState(false)
  const [editing, setEditing] = useState<Review | null>(null)
  const [form, setForm] = useState<Omit<Review, 'id'>>(EMPTY)
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
      const data = await getReviewsList(token)
      setItems(data)
    } catch (err: any) {
      setError(err.message)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    load()
  }, [])

  function openCreate() {
    setEditing(null)
    setForm(EMPTY)
    setFormError('')
    setModalOpen(true)
  }

  function openEdit(item: Review) {
    setEditing(item)
    const { id, ...rest } = item
    setForm(rest)
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
        const updated = await updateReview(token, editing.id, form)
        setItems((prev) => prev.map((item) => item.id === updated.id ? updated : item))
      } else {
        const created = await createReview(token, form)
        setItems((prev) => [created, ...prev])
      }

      setModalOpen(false)
    } catch (err: any) {
      setFormError(err.message)
    } finally {
      setSaving(false)
    }
  }

  async function handleDelete(id: number) {
    if (!confirm('Удалить отзыв?')) return

    const token = getToken()

    if (!token) return

    try {
      await deleteReview(token, id)
      setItems((prev) => prev.filter((item) => item.id !== id))
    } catch (err: any) {
      alert(err.message)
    }
  }

  async function toggleActive(item: Review) {
    const token = getToken()

    if (!token) return

    try {
      const { id, ...rest } = item
      const updated = await updateReview(token, id, { ...rest, is_active: !item.is_active })
      setItems((prev) => prev.map((current) => current.id === updated.id ? updated : current))
    } catch (err: any) {
      alert(err.message)
    }
  }

  function set<K extends keyof typeof form>(field: K, value: (typeof form)[K]) {
    setForm((prev) => ({ ...prev, [field]: value }))
  }

  return (
    <>
      <div className="admin-toolbar">
        <h1 className="admin-page-title" style={{ margin: 0 }}>Отзывы</h1>
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
                <th>Автор</th>
                <th>Текст</th>
                <th>Оценка</th>
                <th>Дата</th>
                <th>Статус</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {items.length === 0 ? (
                <tr>
                  <td colSpan={6} className="admin-empty">Нет отзывов</td>
                </tr>
              ) : items.map((item) => (
                <tr key={item.id}>
                  <td>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                      {item.photo_url && (
                        <img
                          src={item.photo_url}
                          alt={item.author_name}
                          style={{ width: 32, height: 32, borderRadius: '50%', objectFit: 'cover' }}
                        />
                      )}
                      <span style={{ fontWeight: 500 }}>{item.author_name}</span>
                    </div>
                  </td>
                  <td style={{ maxWidth: 280, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                    {item.text}
                  </td>
                  <td><Stars rating={item.rating} /></td>
                  <td style={{ color: 'var(--gray)', fontSize: 13 }}>{item.date}</td>
                  <td>
                    <button
                      onClick={() => toggleActive(item)}
                      className={`admin-badge ${item.is_active ? 'admin-badge-green' : 'admin-badge-gray'}`}
                      style={{ cursor: 'pointer', border: 'none' }}
                    >
                      {item.is_active ? 'Активен' : 'Скрыт'}
                    </button>
                  </td>
                  <td>
                    <div className="admin-table-actions">
                      <button className="btn-admin-secondary" onClick={() => openEdit(item)}>Изменить</button>
                      <button className="btn-admin-danger" onClick={() => handleDelete(item.id)}>Удалить</button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {modalOpen && (
        <div className="admin-modal-overlay" onClick={(e) => e.target === e.currentTarget && setModalOpen(false)}>
          <div className="admin-modal">
            <div className="admin-modal-header">
              <p className="admin-modal-title">{editing ? 'Редактировать отзыв' : 'Добавить отзыв'}</p>
              <button className="admin-modal-close" onClick={() => setModalOpen(false)}>×</button>
            </div>

            {formError && <div className="admin-error">{formError}</div>}

            <div className="admin-form-grid">
              <div className="admin-field">
                <label className="admin-label">Имя автора</label>
                <input
                  className="admin-input"
                  value={form.author_name}
                  onChange={(e) => set('author_name', e.target.value)}
                  placeholder="Иван Иванов"
                />
              </div>

              <div className="admin-field">
                <label className="admin-label">Дата</label>
                <input
                  className="admin-input"
                  type="date"
                  value={form.date}
                  onChange={(e) => set('date', e.target.value)}
                />
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">Текст отзыва</label>
                <textarea
                  className="admin-textarea"
                  style={{ minHeight: 100 }}
                  value={form.text}
                  onChange={(e) => set('text', e.target.value)}
                  placeholder="Текст отзыва..."
                />
              </div>

              <div className="admin-field">
                <label className="admin-label">Оценка</label>
                <select
                  className="admin-select"
                  value={form.rating}
                  onChange={(e) => set('rating', Number(e.target.value))}
                >
                  {[5, 4, 3, 2, 1].map((n) => (
                    <option key={n} value={n}>{'★'.repeat(n)} {n}/5</option>
                  ))}
                </select>
              </div>

              <div className="admin-field">
                <label className="admin-label">Статус</label>
                <select
                  className="admin-select"
                  value={form.is_active ? 'true' : 'false'}
                  onChange={(e) => set('is_active', e.target.value === 'true')}
                >
                  <option value="true">Активен (виден на сайте)</option>
                  <option value="false">Скрыт</option>
                </select>
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">Фото (URL)</label>
                <input
                  className="admin-input"
                  value={form.photo_url ?? ''}
                  onChange={(e) => set('photo_url', e.target.value)}
                  placeholder="https://..."
                />
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">Ссылка на источник</label>
                <input
                  className="admin-input"
                  value={form.source_url ?? ''}
                  onChange={(e) => set('source_url', e.target.value)}
                  placeholder="https://yandex.ru/maps/org/..."
                />
              </div>
            </div>

            <div className="admin-modal-footer">
              <button className="btn-admin-secondary" onClick={() => setModalOpen(false)}>Отмена</button>
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
