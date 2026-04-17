'use client'

import { useState } from 'react'

const footerColumns = [
  [
    { label: 'Для дома', href: 'https://alfasklad.ru/storage/' },
    { label: 'Возможности и преимущества для физ. лиц', href: 'https://alfasklad.ru/features_for_personal/' },
    { label: 'Для бизнеса', href: 'https://alfasklad.ru/for_business/' },
    { label: 'Возможности и преимущества для бизнеса', href: 'https://alfasklad.ru/features_for_business/' },
    { label: 'Мой заказ', href: 'https://alfasklad.ru/cart/' },
  ],
  [
    { label: 'О компании', href: 'https://alfasklad.ru/about/' },
    { label: 'Купим или арендуем помещение', href: 'https://alfasklad.ru/about/kupim-ili-arenduem-sklad/' },
    { label: 'Полезная информация', href: 'https://alfasklad.ru/blog/' },
    { label: 'Новости', href: 'https://alfasklad.ru/news/' },
    { label: 'Вакансии', href: 'https://alfasklad.ru/vacancies/' },
    { label: 'Карта сайта', href: 'https://alfasklad.ru/karta-saita/' },
  ],
  [
    { label: 'Онлайн аренда', href: 'https://alfasklad.ru/rental_catalog/' },
    { label: 'Доставка', href: 'https://alfasklad.ru/services/dostavka/' },
    { label: 'Фотогалерея', href: 'https://alfasklad.ru/about/fotogalereya-skladov/' },
    { label: 'Личный кабинет', href: 'https://alfasklad.ru/cabinet/' },
    { label: 'Оплата', href: 'https://alfasklad.ru/pay/' },
    { label: 'Вакансии', href: 'https://alfasklad.ru/vacancies/' },
  ],
  [
    { label: 'Цены', href: 'https://alfasklad.ru/price/' },
    { label: 'Акции', href: 'https://alfasklad.ru/rental_catalog/' },
    { label: 'Услуги', href: 'https://alfasklad.ru/services/' },
    { label: 'Отзывы', href: 'https://alfasklad.ru/about/reviews/' },
    { label: 'Документы', href: 'https://alfasklad.ru/about/documents/' },
    { label: 'Мобильное приложение', href: 'https://alfasklad.ru/news/mobilnoe-prilozhenie-alfasklad/' },
  ],
]

const reviews = [
  {
    href: 'https://yandex.ru/maps/org/alfasklad/1374078280/reviews/?ll=37.865463%2C55.779039&z=16',
    src: 'https://alfasklad.ru/images/yandex-review-logo.png',
    alt: 'Yandex review',
  },
  {
    href: 'https://2gis.ru/moscow/firm/4504128908435654/tab/reviews?m=37.553983%2C55.853457%2F16.65',
    src: 'https://alfasklad.ru/images/google-review-logo.png',
    alt: '2GIS review',
  },
  {
    href: 'https://alfasklad.ru/about/reviews/',
    src: 'https://alfasklad.ru/images/otzovik-logo.png',
    alt: 'Otzovik review',
  },
]

const payments = [
  { src: 'https://alfasklad.ru/images/sber-logo-v3.svg', alt: 'Sber' },
  { src: 'https://alfasklad.ru/images/iomoney.svg', alt: 'YooMoney' },
  { src: 'https://alfasklad.ru/images/sbp.svg', alt: 'SBP' },
  { src: 'https://alfasklad.ru/images/mastercard.svg', alt: 'Mastercard' },
  { src: 'https://alfasklad.ru/images/visa.svg', alt: 'Visa' },
  { src: 'https://alfasklad.ru/images/sim.svg', alt: 'Mir' },
]

export default function OnlineFooter() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

  return (
    <footer className="online-footer">
      <div className="online-footer__top">
        <div className="online-maxwidth">
          <div className="online-footer__mobile-toggle">
            <button
              type="button"
              className="online-footer__nav-button"
              onClick={() => setMobileMenuOpen((prev) => !prev)}
            >
              Навигация по сайту
            </button>
          </div>

          <div className={`online-footer__grid${mobileMenuOpen ? ' is-open' : ''}`}>
            {footerColumns.map((column, index) => (
              <div key={index} className="online-footer__column online-footer__nav-column">
                {column.map((link) => (
                  <a key={link.href + link.label} className="online-footer__link" href={link.href}>
                    {link.label}
                  </a>
                ))}
              </div>
            ))}

            <div className="online-footer__contact">
              <a className="online-footer__subscribe" href="https://alfasklad.ru/about/">
                Подписка на рассылку
              </a>
              <a className="online-footer__phone" href="tel:+74952663974">
                +7 (495) 266–39–74
              </a>
              <a className="online-footer__text-link" href="mailto:info@alfasklad.ru">
                info@alfasklad.ru
              </a>
              <div className="online-footer__address">Москва, Гостиничный проезд, 6, корп. 2.</div>
            </div>
          </div>

          <div className="online-footer__widgets">
            <div className="online-footer__reviews">
              {reviews.map((review) => (
                <a key={review.alt} href={review.href} target="_blank" rel="noreferrer">
                  <img src={review.src} alt={review.alt} />
                </a>
              ))}
            </div>

            <div className="online-footer__payments">
              <span className="online-footer__payments-title">Принимаем к оплате</span>
              {payments.map((payment) => (
                <img key={payment.alt} src={payment.src} alt={payment.alt} />
              ))}
            </div>

            <div className="online-footer__apps">
              <a
                href="https://apps.apple.com/ru/app/%D0%B0%D0%BB%D1%8C%D1%84%D0%B0%D1%81%D0%BA%D0%BB%D0%B0%D0%B4/id1508117125"
                target="_blank"
                rel="noreferrer"
              >
                <img
                  src="https://alfasklad.ru/images/app-store-badge.svg"
                  alt="Приложение Альфасклад в App Store"
                />
              </a>
            </div>
          </div>
        </div>
      </div>

      <div className="online-footer__bottom">
        <div className="online-maxwidth">
          <div className="online-footer__bottom-row">
            <div className="online-footer__copy">© АльфаСклад 2010-2026</div>
            <div className="online-footer__meta">
              <button type="button" onClick={() => window.print()}>
                Версия для печати
              </button>
              <a href="https://alfasklad.ru/include/licenses_detail.php">Политика конфиденциальности</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  )
}
