import type { Review } from '@/types/admin'

interface Props {
  reviews: Review[]
}

function Stars({ rating }: { rating: number }) {
  return (
    <div className="review-stars" aria-label={`Оценка ${rating} из 5`}>
      {Array.from({ length: 5 }).map((_, i) => (
        <span key={i} className={i < rating ? 'star-filled' : 'star-empty'}>★</span>
      ))}
    </div>
  )
}

export function ReviewsSection({ reviews }: Props) {
  if (reviews.length === 0) return null

  return (
    <section className="reviews-section">
      <div className="container">
        <div className="reviews-header">
          <h2 className="reviews-title">Отзывы клиентов</h2>
          <p className="reviews-sub">Что говорят о нас арендаторы</p>
        </div>

        <div className="reviews-grid">
          {reviews.map((review) => (
            <article key={review.id} className="review-card">
              <Stars rating={review.rating} />

              <p className="review-text">{review.text}</p>

              <div className="review-author">
                {review.photo_url && (
                  <img
                    src={review.photo_url}
                    alt={review.author_name}
                    className="review-avatar"
                    width={40}
                    height={40}
                  />
                )}
                <div className="review-author-info">
                  <span className="review-author-name">{review.author_name}</span>
                  <span className="review-date">
                    {new Date(review.date).toLocaleDateString('ru-RU', {
                      day: 'numeric',
                      month: 'long',
                      year: 'numeric',
                    })}
                  </span>
                </div>
                {review.source_url && (
                  <a
                    href={review.source_url}
                    className="review-source-link"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Источник отзыва"
                  >
                    ↗
                  </a>
                )}
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  )
}
