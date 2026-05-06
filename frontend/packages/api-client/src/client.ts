import { ApiError, type ApiErrorCode } from './types';

export type FetchConfig = {
  // Передаётся из Server Components через cookies().toString()
  cookieHeader?: string;
};

function getBaseUrl(): string {
  if (typeof window === 'undefined') {
    // Server-side: внутренний URL (не через Nginx)
    const url = process.env.API_BASE_URL;
    if (!url) throw new Error('API_BASE_URL is not set');
    return url;
  }
  // Client-side: относительный путь, Nginx проксирует /api/ → Laravel
  return process.env.NEXT_PUBLIC_API_URL ?? '/api';
}

export async function apiFetch<T>(
  path: string,
  options: RequestInit & FetchConfig = {},
): Promise<T> {
  const { cookieHeader, ...rest } = options;

  const headers: Record<string, string> = {
    'Content-Type': 'application/json',
    ...(rest.headers as Record<string, string>),
  };

  if (cookieHeader) {
    headers['Cookie'] = cookieHeader;
  }

  const res = await fetch(`${getBaseUrl()}${path}`, {
    ...rest,
    headers,
    // Client-side: отправлять Sanctum cookie автоматически
    credentials: typeof window !== 'undefined' ? 'include' : 'omit',
  });

  if (!res.ok) {
    const body = await res.json().catch(() => ({}));
    const code = (body?.error?.code ?? 'UNKNOWN') as ApiErrorCode;
    throw new ApiError(code, res.status);
  }

  return res.json() as Promise<T>;
}
