'use client'

import { useState } from 'react'
import Image from 'next/image'

const BITRIX_BASE = 'https://alfasklad.ru'

interface Props {
  photoUrl: string | null
  boxNumber: string
}

// В будущем API вернёт массив фото — пока работаем с одним
// Компонент уже готов к массиву
export function BoxGallery({ photoUrl, boxNumber }: Props) {
  const photos = photoUrl ? [`${BITRIX_BASE}${photoUrl}`] : []
  const [active, setActive] = useState(0)

  if (photos.length === 0) {
    return (
      <div className="gallery-empty">
        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" aria-hidden="true">
          <rect x="6" y="14" width="44" height="34" rx="3" stroke="#C4C0B8" strokeWidth="1.5" />
          <path d="M6 26 L20 18 L32 26 L44 19 L50 24" stroke="#C4C0B8" strokeWidth="1.5" />
          <circle cx="40" cy="24" r="5" stroke="#C4C0B8" strokeWidth="1.5" />
        </svg>
        <p>Фото бокса {boxNumber} пока нет</p>
      </div>
    )
  }

  return (
    <div className="gallery">
      {/* Главное фото */}
      <div className="gallery-main">
        <Image
          src={photos[active]}
          alt={`Бокс ${boxNumber}`}
          fill
          sizes="(max-width: 768px) 100vw, 560px"
          className="gallery-img"
          priority
        />
      </div>

      {/* Миниатюры (когда появится массив фото) */}
      {photos.length > 1 && (
        <div className="gallery-thumbs">
          {photos.map((src, i) => (
            <button
              key={i}
              className={`gallery-thumb${active === i ? ' active' : ''}`}
              onClick={() => setActive(i)}
              aria-label={`Фото ${i + 1}`}
            >
              <Image
                src={src}
                alt=""
                fill
                sizes="80px"
                className="gallery-img"
              />
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
