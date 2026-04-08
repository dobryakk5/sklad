import type { AdminAuthResponse, AdminUser, Review, SeoMeta } from '@/types/admin'

function getApiBase(): string {
  const base =
    process.env.NEXT_PUBLIC_API_URL ??
    'http://127.0.0.1:8000/api'

  return base.replace(/\/+$/, '').replace(/\/api$/, '')
}

export async function adminLogin(email: string, password: string): Promise<AdminAuthResponse> {
  const res = await fetch(`${getApiBase()}/admin/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password }),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.error ?? 'Ошибка авторизации')
  }

  return json.data
}

export async function adminLogout(token: string): Promise<void> {
  await fetch(`${getApiBase()}/admin/logout`, {
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
  })
}

export async function adminForgotPassword(email: string): Promise<void> {
  const res = await fetch(`${getApiBase()}/admin/forgot-password`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email }),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.error ?? 'Ошибка восстановления пароля')
  }
}

export async function adminResetPassword(
  token: string,
  email: string,
  password: string,
  password_confirmation: string,
): Promise<void> {
  const res = await fetch(`${getApiBase()}/admin/reset-password`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ token, email, password, password_confirmation }),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.error ?? 'Ошибка сброса пароля')
  }
}

export async function getSeoList(token: string): Promise<SeoMeta[]> {
  const res = await fetch(`${getApiBase()}/admin/seo`, {
    headers: { Authorization: `Bearer ${token}` },
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.error ?? 'Ошибка загрузки SEO')
  }

  return json.data
}

export async function createSeoMeta(token: string, data: Omit<SeoMeta, 'id'>): Promise<SeoMeta> {
  const res = await fetch(`${getApiBase()}/admin/seo`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(data),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.message ?? json.error ?? 'Ошибка создания')
  }

  return json.data
}

export async function updateSeoMeta(
  token: string,
  id: number,
  data: Omit<SeoMeta, 'id'>,
): Promise<SeoMeta> {
  const res = await fetch(`${getApiBase()}/admin/seo/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(data),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.message ?? json.error ?? 'Ошибка обновления')
  }

  return json.data
}

export async function deleteSeoMeta(token: string, id: number): Promise<void> {
  const res = await fetch(`${getApiBase()}/admin/seo/${id}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${token}` },
  })

  if (!res.ok) {
    throw new Error('Ошибка удаления')
  }
}

export async function getReviewsList(token: string): Promise<Review[]> {
  const res = await fetch(`${getApiBase()}/admin/reviews`, {
    headers: { Authorization: `Bearer ${token}` },
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.error ?? 'Ошибка загрузки отзывов')
  }

  return json.data
}

export async function createReview(token: string, data: Omit<Review, 'id'>): Promise<Review> {
  const res = await fetch(`${getApiBase()}/admin/reviews`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(data),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.message ?? json.error ?? 'Ошибка создания')
  }

  return json.data
}

export async function updateReview(
  token: string,
  id: number,
  data: Omit<Review, 'id'>,
): Promise<Review> {
  const res = await fetch(`${getApiBase()}/admin/reviews/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(data),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.message ?? json.error ?? 'Ошибка обновления')
  }

  return json.data
}

export async function deleteReview(token: string, id: number): Promise<void> {
  const res = await fetch(`${getApiBase()}/admin/reviews/${id}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${token}` },
  })

  if (!res.ok) {
    throw new Error('Ошибка удаления')
  }
}

export async function getUsersList(token: string): Promise<AdminUser[]> {
  const res = await fetch(`${getApiBase()}/admin/users`, {
    headers: { Authorization: `Bearer ${token}` },
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.error ?? 'Ошибка загрузки пользователей')
  }

  return json.data
}

export async function createUser(
  token: string,
  data: { name: string; email: string; password: string; role: 'admin' | 'operator' },
): Promise<AdminUser> {
  const res = await fetch(`${getApiBase()}/admin/users`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(data),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.message ?? json.error ?? 'Ошибка создания')
  }

  return json.data
}

export async function updateUser(
  token: string,
  id: number,
  data: { name: string; email: string; role: 'admin' | 'operator'; password?: string },
): Promise<AdminUser> {
  const res = await fetch(`${getApiBase()}/admin/users/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(data),
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.message ?? json.error ?? 'Ошибка обновления')
  }

  return json.data
}

export async function deleteUser(token: string, id: number): Promise<void> {
  const res = await fetch(`${getApiBase()}/admin/users/${id}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${token}` },
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.error ?? 'Ошибка удаления')
  }
}
