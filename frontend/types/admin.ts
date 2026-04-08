export type SeoPageType = 'warehouse' | 'box'

export interface SeoMeta {
  id: number
  page_type: SeoPageType
  page_slug: string
  title: string | null
  description: string | null
  canonical: string | null
  og_title: string | null
  og_description: string | null
  og_image: string | null
}

export interface SeoOverride {
  title: string | null
  description: string | null
  canonical: string | null
  og_title: string | null
  og_description: string | null
  og_image: string | null
}

export interface Review {
  id: number
  author_name: string
  text: string
  rating: number
  date: string
  photo_url: string | null
  source_url: string | null
  is_active: boolean
}

export interface AdminUser {
  id: number
  name: string
  email: string
  role: 'admin' | 'operator'
  created_at: string | null
}

export interface AdminAuthResponse {
  token: string
  name: string
  email: string
  role: 'admin' | 'operator'
}
