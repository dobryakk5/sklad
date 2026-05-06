import { apiFetch, type FetchConfig } from '../client';
import type { ApiUser } from '../types';

export async function login(
  login: string,
  password: string,
): Promise<{ user: ApiUser }> {
  return apiFetch('/cabinet/auth/login', {
    method: 'POST',
    body: JSON.stringify({ login, password }),
  });
}

export async function logout(): Promise<void> {
  await apiFetch('/cabinet/auth/logout', { method: 'POST' });
}

export async function getMe(config: FetchConfig = {}): Promise<ApiUser> {
  return apiFetch('/cabinet/me', config);
}
