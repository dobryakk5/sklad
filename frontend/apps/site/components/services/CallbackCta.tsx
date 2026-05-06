'use client'

import { useState } from 'react'

export default function CallbackCta({ title = 'Остались вопросы?' }: { title?: string }) {
  const [sent, setSent] = useState(false)

  return (
    <section className="svc-demo-cta">
      <div className="container">
        <div className="svc-demo-cta-box">
          <p className="svc-eyebrow svc-eyebrow-light">Тестовый сценарий</p>
          <h2 className="svc-demo-title">{title}</h2>
          <p className="svc-demo-copy">
            Этот блок показывает только демонстрацию интерфейса. Заявка никуда не отправляется.
          </p>

          {!sent ? (
            <>
              <div className="svc-demo-actions">
                <input
                  type="tel"
                  placeholder="+7 (___) ___-__-__"
                  className="svc-demo-input"
                  aria-label="Телефон для тестового звонка"
                />
                <button type="button" className="svc-demo-button" onClick={() => setSent(true)}>
                  Тестовый звонок
                </button>
              </div>
              <p className="svc-demo-note">
                После клика показывается только тестовый success-state без реальной отправки.
              </p>
            </>
          ) : (
            <div className="svc-demo-success">
              Демонстрация завершена: интерфейс переключился, но реальная заявка не создавалась.
            </div>
          )}
        </div>
      </div>
    </section>
  )
}
