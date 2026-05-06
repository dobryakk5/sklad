'use client'

import { useState, useEffect, useRef } from 'react'
import { CABINET_URL } from '@/lib/constants'

type IframeState = 'loading' | 'ready' | 'error'

// Таймаут для обнаружения блокировки X-Frame-Options / CSP.
// Браузер не генерирует onerror для CSP-блокировок — iframe «загружается»
// (onLoad срабатывает), но остаётся пустым. Если onLoad не пришёл
// за LOAD_TIMEOUT_MS — переходим в состояние error.
const LOAD_TIMEOUT_MS = 12_000

export function CabinetIframe() {
  const [state, setState] = useState<IframeState>('loading')
  const [height, setHeight] = useState(700)
  const iframeRef = useRef<HTMLIFrameElement>(null)
  const timeoutRef = useRef<ReturnType<typeof setTimeout> | null>(null)

  useEffect(() => {
    function onMessage(e: MessageEvent) {
      if (e.origin !== 'https://alfasklad.ru') return
      if (typeof e.data?.height === 'number') {
        setHeight(Math.max(600, e.data.height))
      }
    }
    window.addEventListener('message', onMessage)
    return () => window.removeEventListener('message', onMessage)
  }, [])

  useEffect(() => {
    if (state !== 'loading') return
    timeoutRef.current = setTimeout(() => setState('error'), LOAD_TIMEOUT_MS)
    return () => { if (timeoutRef.current) clearTimeout(timeoutRef.current) }
  }, [state])

  function handleLoad() {
    if (timeoutRef.current) clearTimeout(timeoutRef.current)
    setState('ready')
  }

  function handleError() {
    if (timeoutRef.current) clearTimeout(timeoutRef.current)
    setState('error')
  }

  function handleRetry() {
    setState('loading')
    if (iframeRef.current) iframeRef.current.src = CABINET_URL
  }

  return (
    <div className="cabinet-iframe-wrap">
      {state === 'loading' && (
        <div className="cabinet-loader" aria-live="polite">
          <div className="cabinet-loader-spinner" />
          <p>Загружаем личный кабинет…</p>
        </div>
      )}

      {state === 'error' && (
        <div className="cabinet-error">
          <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
            <circle cx="20" cy="20" r="18" stroke="#C4C0B8" strokeWidth="1.5" />
            <path d="M20 12v10M20 26v2" stroke="#C4C0B8" strokeWidth="2" strokeLinecap="round" />
          </svg>
          <p className="cabinet-error-title">Не удалось загрузить личный кабинет</p>
          <p className="cabinet-error-sub">
            Попробуйте обновить страницу или откройте кабинет напрямую
          </p>
          <div className="cabinet-error-actions">
            <button className="btn-primary" onClick={handleRetry}>Обновить</button>
            <a href={CABINET_URL} target="_blank" rel="noopener noreferrer" className="btn-outline-dark">
              Открыть на alfasklad.ru ↗
            </a>
          </div>
        </div>
      )}

      <iframe
        ref={iframeRef}
        src={CABINET_URL}
        title="Личный кабинет АльфаСклад"
        width="100%"
        style={{ height: `${height}px`, display: state === 'error' ? 'none' : 'block' }}
        onLoad={handleLoad}
        onError={handleError}
        allow="payment"
        referrerPolicy="origin"
        className="cabinet-iframe"
      />
    </div>
  )
}
