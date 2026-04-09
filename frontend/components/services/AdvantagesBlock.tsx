const advantages = [
  'Доступ к своим вещам 24/7',
  'Бесплатное оборудование для погрузки вещей',
  'Высота бокса 2,8 метра и объём 1 кв. м = 2,8 куб. м',
  'Видеонаблюдение, охрана и сигнализация',
  'Освещение в боксах',
  'Доступ к Wi-Fi на территории комплекса',
  'Акции и скидки для новых и постоянных клиентов',
  'Контроль температуры',
]

export default function AdvantagesBlock() {
  return (
    <section className="svc-section svc-section-muted">
      <div className="container">
        <div className="svc-section-heading">
          <p className="svc-eyebrow">Преимущества</p>
          <h2 className="svc-section-title">Почему выбирают АльфаСклад</h2>
        </div>

        <div className="svc-grid svc-grid-advantages">
          {advantages.map((advantage) => (
            <article key={advantage} className="svc-advantage-card">
              <span className="svc-advantage-icon" aria-hidden="true">✓</span>
              <p>{advantage}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  )
}
