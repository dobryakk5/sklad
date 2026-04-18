'use client'

import { useMemo, useState } from 'react'

const LOGO = 'https://alfasklad.ru/upload/CPriority/b35/guv308nx3nh9ugbbpt4m6252s40syyit/logo_site.png'

const mainNav = [
  {
    label: 'Главная',
    href: 'https://alfasklad.ru/',
    children: [],
  },
  {
    label: 'Аренда',
    href: 'https://alfasklad.ru/rental_catalog/',
    children: [
      { label: 'Аренда бокса', href: 'https://alfasklad.ru/rental_catalog/' },
      { label: 'Аренда контейнера', href: 'https://alfasklad.ru/rental_catalog/konteynery/' },
      { label: 'Аренда ячейки', href: 'https://alfasklad.ru/rental_catalog/yacheyka/' },
      { label: 'Аренда кладовки', href: 'https://alfasklad.ru/rental_catalog/kladovki/' },
      { label: 'Аренда помещения', href: 'https://alfasklad.ru/rental_catalog/arenda_pomeshcheniya/' },
    ],
  },
  {
    label: 'Услуги',
    href: 'https://alfasklad.ru/services/',
    children: [
      { label: 'Для дома', href: 'https://alfasklad.ru/storage/' },
      { label: 'Для бизнеса', href: 'https://alfasklad.ru/for_business/' },
      { label: 'Доставка', href: 'https://alfasklad.ru/services/dostavka/' },
    ],
  },
  { label: 'Цены', href: 'https://alfasklad.ru/price/', children: [] },
  { label: 'Блог', href: 'https://alfasklad.ru/blog/', children: [] },
]

const cities = [
  { label: 'Москва', href: 'https://alfasklad.ru/' },
  { label: 'Санкт-Петербург', href: 'https://spb.alfasklad.ru/' },
]

function Caret() {
  return (
    <svg className="online-nav-item__caret" viewBox="0 0 10 6" fill="none" aria-hidden="true">
      <path d="M1 1L5 5L9 1" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
    </svg>
  )
}

function DesktopNavItem({
  item,
  menuTone = 'light',
}: {
  item: { label: string; href: string; children: { label: string; href: string }[] }
  menuTone?: 'light' | 'red'
}) {
  const [open, setOpen] = useState(false)
  const hasChildren = item.children.length > 0

  return (
    <div className="online-nav-item" onMouseEnter={() => setOpen(true)} onMouseLeave={() => setOpen(false)}>
      <a
        href={item.href}
        className={menuTone === 'red' ? 'online-nav-item__toggle' : 'online-nav-item__link'}
      >
        <span>{item.label}</span>
        {hasChildren && <Caret />}
      </a>
      {hasChildren && open && (
        <div className="online-nav-item__menu">
          {item.children.map((child) => (
            <a
              key={child.href}
              href={child.href}
              onClick={() => setOpen(false)}
            >
              {child.label}
            </a>
          ))}
        </div>
      )}
    </div>
  )
}

export default function OnlineHeader() {
  const [currentCity, setCurrentCity] = useState(cities[0])
  const [cityOpen, setCityOpen] = useState(false)
  const [mobileOpen, setMobileOpen] = useState(false)

  const mobileLinks = useMemo(
    () =>
      [...mainNav].map((item) => ({
        label: item.label,
        href: item.href,
      })),
    [],
  )

  return (
    <header className="online-header">
      <div className="online-header__desktop">
        <div className="online-maxwidth">
          <div className="online-header__main-row">
            <a className="online-header__logo" href="https://alfasklad.ru/" aria-label="АльфаСклад">
              <img src={LOGO} alt="АльфаСклад" />
            </a>

            <div className="online-header__city">
              <button
                type="button"
                className="online-header__city-toggle"
                onClick={() => setCityOpen((prev) => !prev)}
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="12" viewBox="0 0 10 12" fill="none">
                  <path
                    d="M8 10H7.024L6.414 10.61L5 12.024L3.586 10.61L2.976 10H2C0.896 10 0 9.104 0 8V0H10V8C10 9.104 9.104 10 8 10ZM8 5V2H2V8H3.8L5 9.2L6.2 8H8V5Z"
                    fill="#333"
                  />
                </svg>
                <span>{currentCity.label}</span>
                <Caret />
              </button>
              {cityOpen && (
                <div className="online-header__city-menu">
                  {cities.map((city) => (
                    <button
                      key={city.label}
                      type="button"
                      className={city.label === currentCity.label ? 'is-active' : undefined}
                      onClick={() => {
                        setCurrentCity(city)
                        setCityOpen(false)
                      }}
                    >
                      {city.label}
                    </button>
                  ))}
                </div>
              )}
            </div>

            <div className="online-header__contacts">
              <div className="online-header__contact">
                <div className="online-header__contact-label">Звоните</div>
                <a className="online-header__contact-main" href="tel:+74951911046">
                  +7 (495) 191–10–46
                </a>
              </div>

              <div className="online-header__contact">
                <div className="online-header__contact-label">Пишите</div>
                <a className="online-header__contact-sub" href="https://widget.yourgood.app/#telegram">
                  <span
                    style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      width: 30,
                      height: 30,
                      borderRadius: '50%',
                      background: '#38a8e0',
                    }}
                  >
                    <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path
                        d="M14.7433 1.01865L1.92006 5.96455C1.0457 6.3151 1.05076 6.80205 1.75935 7.0196L5.05029 8.0461L12.6627 3.24316C13.0227 3.02436 13.3522 3.14203 13.0827 3.3816L6.91431 8.94843H6.91286L6.91431 8.94916L6.68723 12.2866C7.02079 12.2866 7.16816 12.1338 7.3553 11.9534L8.95368 10.3992L12.2782 12.8566C12.8909 13.1943 13.3312 13.0205 13.4838 12.2873L15.6664 1.99984C15.8901 1.10149 15.3276 0.694337 14.7433 1.01865Z"
                        fill="white"
                      />
                    </svg>
                  </span>
                  <span>Telegram</span>
                </a>
              </div>
            </div>

            <div className="online-header__actions">
              <a className="online-header__button online-header__button--accent" href="https://alfasklad.ru/cabinet/">
                Личный кабинет
              </a>
            </div>
          </div>
        </div>

        <div className="online-header__menu-bar">
          <div className="online-maxwidth">
            <div className="online-header__menu-row">
              <nav className="online-header__menu-links" aria-label="Основная навигация">
                {mainNav.map((item) => (
                  <DesktopNavItem key={item.href} item={item} menuTone="red" />
                ))}
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div className="online-header__mobile">
        <div className="online-maxwidth">
          <div className="online-header__mobile-row">
            <button
              type="button"
              className="online-header__burger"
              aria-label={mobileOpen ? 'Закрыть меню' : 'Открыть меню'}
              onClick={() => setMobileOpen((prev) => !prev)}
            >
              {mobileOpen ? '×' : '☰'}
            </button>

            <a className="online-header__logo" href="https://alfasklad.ru/" aria-label="АльфаСклад">
              <img src={LOGO} alt="АльфаСклад" />
            </a>

            <div className="online-header__mobile-actions">
              <a className="online-header__mobile-link" href="tel:+74952663974" aria-label="Позвонить">
                ☎
              </a>
              <a className="online-header__mobile-link" href="https://alfasklad.ru/cabinet/" aria-label="Личный кабинет">
                ⌂
              </a>
            </div>
          </div>
        </div>

        {mobileOpen && (
          <div className="online-header__mobile-panel">
            <div className="online-maxwidth">
              <div className="online-header__mobile-panel-inner">
                <div className="online-header__mobile-block">
                  <div className="online-header__mobile-title">Город</div>
                  <div className="online-header__mobile-links">
                    {cities.map((city) => (
                      <button
                        key={city.label}
                        type="button"
                        onClick={() => {
                          setCurrentCity(city)
                          setMobileOpen(false)
                        }}
                      >
                        {city.label}
                      </button>
                    ))}
                  </div>
                </div>

                <div className="online-header__mobile-block">
                  <div className="online-header__mobile-title">Навигация</div>
                  <div className="online-header__mobile-links">
                    {mobileLinks.map((item) => (
                      <a key={`${item.href}-${item.label}`} href={item.href}>
                        {item.label}
                      </a>
                    ))}
                  </div>
                </div>

                <div className="online-header__mobile-block">
                  <div className="online-header__mobile-title">Контакты</div>
                  <div className="online-header__mobile-links">
                    <a href="tel:+74951911046">+7 (495) 191–10–46</a>
                    <a href="https://widget.yourgood.app/#telegram">Telegram</a>
                    <a href={currentCity.href}>{currentCity.label}</a>
                    <a href="https://alfasklad.ru/cabinet/">Личный кабинет</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </header>
  )
}
