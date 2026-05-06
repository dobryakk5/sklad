import { redirect } from 'next/navigation';
import { ApiError } from '@alfasklad/api-client';

export async function fetchProtected<T>(promise: Promise<T>): Promise<T> {
  try {
    return await promise;
  } catch (err) {
    if (err instanceof ApiError && err.isUnauthorized()) {
      redirect('/login');
    }

    throw err;
  }
}
