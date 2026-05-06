'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import {
  createSeoMeta,
  deleteSeoMeta,
  getSeoList,
  updateSeoMeta,
} from '@/lib/admin-api'
import type { SeoMeta } from '@/types/admin'

const EMPTY: Omit<SeoMeta, 'id'> = {
  page_type: 'warehouse',
  page_slug: '',
  title: '',
  description: '',
  canonical: '',
  og_title: '',
  og_description: '',
  og_image: '',
}

export default function AdminSeoPage() {
  const router = useRouter()
  const [items, setItems] = useState<SeoMeta[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [modalOpen, setModalOpen] = useState(false)
  const [editing, setEditing] = useState<SeoMeta | null>(null)
  const [form, setForm] = useState<Omit<SeoMeta, 'id'>>(EMPTY)
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
      const data = await getSeoList(token)
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

  function openEdit(item: SeoMeta) {
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
        const updated = await updateSeoMeta(token, editing.id, form)
        setItems((prev) => prev.map((item) => item.id === updated.id ? updated : item))
      } else {
        const created = await createSeoMeta(token, form)
        setItems((prev) => [...prev, created])
      }

      setModalOpen(false)
    } catch (err: any) {
      setFormError(err.message)
    } finally {
      setSaving(false)
    }
  }

  async function handleDelete(id: number) {
    if (!confirm('Удалить SEO запись?')) return

    const token = getToken()

    if (!token) return

    try {
      await deleteSeoMeta(token, id)
      setItems((prev) => prev.filter((item) => item.id !== id))
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
        <h1 className="admin-page-title" style={{ margin: 0 }}>SEO мета</h1>
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
                <th>Тип</th>
                <th>Slug</th>
                <th>Title</th>
                <th>Description</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {items.length === 0 ? (
                <tr>
                  <td colSpan={5} className="admin-empty">Нет записей</td>
                </tr>
              ) : items.map((item) => (
                <tr key={item.id}>
                  <td>
                    <span className={`admin-type-tag admin-type-${item.page_type}`}>
                      {item.page_type === 'warehouse' ? 'Склад' : 'Бокс'}
                    </span>
                  </td>
                  <td style={{ fontFamily: 'monospace', fontSize: 13 }}>{item.page_slug}</td>
                  <td style={{ maxWidth: 200, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                    {item.title || <span style={{ color: 'var(--gray-light)' }}>—</span>}
                  </td>
                  <td style={{ maxWidth: 240, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                    {item.description || <span style={{ color: 'var(--gray-light)' }}>—</span>}
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
              <p className="admin-modal-title">{editing ? 'Редактировать SEO' : 'Добавить SEO'}</p>
              <button className="admin-modal-close" onClick={() => setModalOpen(false)}>×</button>
            </div>

            {formError && <div className="admin-error">{formError}</div>}

            <div className="admin-form-grid">
              <div className="admin-field">
                <label className="admin-label">Тип страницы</label>
                <select
                  className="admin-select"
                  value={form.page_type}
                  onChange={(e) => set('page_type', e.target.value as 'warehouse' | 'box')}
                >
                  <option value="warehouse">Склад (warehouse)</option>
                  <option value="box">Бокс (box)</option>
                </select>
              </div>

              <div className="admin-field">
                <label className="admin-label">Slug / ID</label>
                <input
                  className="admin-input"
                  value={form.page_slug}
                  onChange={(e) => set('page_slug', e.target.value)}
                  placeholder={form.page_type === 'warehouse' ? 'sklad-na-sokole' : '12345'}
                />
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">Title</label>
                <input
                  className="admin-input"
                  value={form.title ?? ''}
                  onChange={(e) => set('title', e.target.value)}
                  placeholder="Заголовок страницы"
                />
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">Description</label>
                <textarea
                  className="admin-textarea"
                  value={form.description ?? ''}
                  onChange={(e) => set('description', e.target.value)}
                  placeholder="Мета-описание страницы"
                />
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">Canonical URL</label>
                <input
                  className="admin-input"
                  value={form.canonical ?? ''}
                  onChange={(e) => set('canonical', e.target.value)}
                  placeholder="https://alfasklad.ru/..."
                />
              </div>

              <div className="admin-field admin-field-full" style={{ marginTop: 8, paddingTop: 16, borderTop: '1px solid var(--border)' }}>
                <label className="admin-label" style={{ color: 'var(--gray)', fontSize: 11, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                  Open Graph
                </label>
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">OG Title</label>
                <input
                  className="admin-input"
                  value={form.og_title ?? ''}
                  onChange={(e) => set('og_title', e.target.value)}
                  placeholder="Заголовок для соцсетей"
                />
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">OG Description</label>
                <textarea
                  className="admin-textarea"
                  value={form.og_description ?? ''}
                  onChange={(e) => set('og_description', e.target.value)}
                  placeholder="Описание для соцсетей"
                />
              </div>

              <div className="admin-field admin-field-full">
                <label className="admin-label">OG Image URL</label>
                <input
                  className="admin-input"
                  value={form.og_image ?? ''}
                  onChange={(e) => set('og_image', e.target.value)}
                  placeholder="https://..."
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
