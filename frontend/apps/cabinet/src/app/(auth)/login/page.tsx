'use client';

import { Suspense, useState, type FormEvent } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import { Printer } from 'lucide-react';
import { login, ApiError } from '@alfasklad/api-client';

const DEFAULT_RETURN_PATH = '/';

function getSafeReturnPath(value: string | null): string {
  if (!value || !value.startsWith('/') || value.startsWith('//')) {
    return DEFAULT_RETURN_PATH;
  }

  try {
    const url = new URL(value, 'https://lk.alfasklad.local');

    if (url.origin !== 'https://lk.alfasklad.local') {
      return DEFAULT_RETURN_PATH;
    }

    return `${url.pathname}${url.search}${url.hash}` || DEFAULT_RETURN_PATH;
  } catch {
    return DEFAULT_RETURN_PATH;
  }
}

function LoginForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const from = getSafeReturnPath(searchParams.get('from'));

  const [loginValue, setLoginValue] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      await login(loginValue.trim(), password);
      router.replace(from);
    } catch (err) {
      if (err instanceof ApiError && err.isUnauthorized()) {
        setError('Неверный логин или пароль');
      } else if (err instanceof ApiError && err.code === 'NOT_IMPLEMENTED') {
        setError('Вход временно недоступен: read-only API кабинета ещё не реализован.');
      } else {
        setError('Ошибка сервера. Попробуйте позже.');
      }
    } finally {
      setLoading(false);
    }
  }

  return (
    <main className="login-page">
      <div className="login-shell">
        <a className="login-logo" href="/" aria-label="АльфаСклад">
          <img src="/images/logo_site.png" alt="АльфаСклад" />
        </a>

        <button
          type="button"
          className="login-print"
          aria-label="Печать"
          onClick={() => window.print()}
        >
          <Printer size={18} strokeWidth={2} />
        </button>

        <header className="login-header">
          <h1>Зайти в Личный кабинет</h1>
          <p>
            Для входа в личный кабинет и оплаты счетов введите свой логин (email) и пароль, который был отправлен Вам по email при заключении договора, либо войдите через SMS (если телефон привязан к аккаунту)
          </p>
        </header>

        <div className="login-tabs" aria-label="Способ входа">
          <button type="button" className="login-tab login-tab-active">
            Электронная почта
          </button>
          <button type="button" className="login-tab" disabled>
            Через SMS
          </button>
        </div>

        <form onSubmit={handleSubmit} className="login-form">
          <div className="login-fields">
            <label className="login-field" htmlFor="login">
              <span>E-mail (является логином для входа на сайт) <b>*</b></span>
              <input
                id="login"
                type="text"
                autoComplete="username"
                value={loginValue}
                onChange={(e) => setLoginValue(e.target.value)}
                required
              />
            </label>

            <label className="login-field" htmlFor="password">
              <span>Пароль <b>*</b></span>
              <input
                id="password"
                type="password"
                autoComplete="current-password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
            </label>

            <div className="login-row">
              <label className="login-remember">
                <input type="checkbox" />
                <span>Запомнить меня</span>
              </label>

              <a className="login-forgot" href="mailto:info@alfasklad.ru">
                Забыли пароль?
              </a>
            </div>

            {error && (
              <p className="login-error">{error}</p>
            )}

            <button
              type="submit"
              disabled={loading}
              className="login-submit"
            >
              {loading ? 'Вход...' : 'Войти'}
            </button>
          </div>
        </form>
      </div>
    </main>
  );
}

export default function LoginPage() {
  return (
    <Suspense fallback={<main className="login-page" />}>
      <LoginForm />
    </Suspense>
  );
}
