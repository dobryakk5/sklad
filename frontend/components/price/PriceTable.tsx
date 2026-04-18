'use client'

import Link from 'next/link'
import { useState } from 'react'
import styles from './price.module.css'

type PriceRow = {
  area: number
  price: number
  pricePerM2: number
}

type Warehouse = {
  id: string
  name: string
  address: string
  metro?: string
  prices: PriceRow[]
}

const WAREHOUSES: Warehouse[] = [
  {
    id: 'zvenigorod',
    name: 'Звенигородское ш.',
    address: 'Звенигородское ш., д. 28, стр. 16',
    metro: 'Улица 1905 года',
    prices: [
      { area: 1, price: 2900, pricePerM2: 2900 },
      { area: 2, price: 5200, pricePerM2: 2600 },
      { area: 3, price: 7100, pricePerM2: 2367 },
      { area: 4, price: 8900, pricePerM2: 2225 },
      { area: 6, price: 12500, pricePerM2: 2083 },
      { area: 8, price: 15800, pricePerM2: 1975 },
      { area: 10, price: 18900, pricePerM2: 1890 },
      { area: 15, price: 26500, pricePerM2: 1767 },
    ],
  },
  {
    id: 'verkhnelikh',
    name: 'Верхнелихоборская',
    address: 'Верхнелихоборская ул., д. 8а',
    metro: 'Верхние Лихоборы',
    prices: [
      { area: 1, price: 2800, pricePerM2: 2800 },
      { area: 2, price: 4900, pricePerM2: 2450 },
      { area: 3, price: 6800, pricePerM2: 2267 },
      { area: 4, price: 8500, pricePerM2: 2125 },
      { area: 6, price: 11900, pricePerM2: 1983 },
      { area: 8, price: 15200, pricePerM2: 1900 },
      { area: 10, price: 18200, pricePerM2: 1820 },
      { area: 15, price: 25500, pricePerM2: 1700 },
    ],
  },
  {
    id: 'petrovskiy',
    name: 'Новопетровская',
    address: 'Новопетровская ул., д. 6, ТЦ «Петровский», паркинг –2 уровень',
    metro: 'Митино',
    prices: [
      { area: 1, price: 2600, pricePerM2: 2600 },
      { area: 2, price: 4700, pricePerM2: 2350 },
      { area: 3, price: 6400, pricePerM2: 2133 },
      { area: 4, price: 7900, pricePerM2: 1975 },
      { area: 6, price: 11000, pricePerM2: 1833 },
      { area: 8, price: 14100, pricePerM2: 1763 },
      { area: 10, price: 17000, pricePerM2: 1700 },
      { area: 15, price: 23900, pricePerM2: 1593 },
    ],
  },
  {
    id: 'molodogvard',
    name: 'Молодогвардейская',
    address: 'Молодогвардейская ул., д. 61, стр. 3',
    metro: 'Кунцевская',
    prices: [
      { area: 1, price: 3100, pricePerM2: 3100 },
      { area: 2, price: 5500, pricePerM2: 2750 },
      { area: 3, price: 7500, pricePerM2: 2500 },
      { area: 4, price: 9400, pricePerM2: 2350 },
      { area: 6, price: 13200, pricePerM2: 2200 },
      { area: 8, price: 16800, pricePerM2: 2100 },
      { area: 10, price: 20000, pricePerM2: 2000 },
      { area: 15, price: 28000, pricePerM2: 1867 },
    ],
  },
  {
    id: 'kovshova',
    name: 'Наташи Ковшовой',
    address: 'ул. Наташи Ковшовой, д. 2, стр. 1',
    metro: 'Солнцево',
    prices: [
      { area: 1, price: 2700, pricePerM2: 2700 },
      { area: 2, price: 4800, pricePerM2: 2400 },
      { area: 3, price: 6600, pricePerM2: 2200 },
      { area: 4, price: 8200, pricePerM2: 2050 },
      { area: 6, price: 11500, pricePerM2: 1917 },
      { area: 8, price: 14700, pricePerM2: 1838 },
      { area: 10, price: 17500, pricePerM2: 1750 },
      { area: 15, price: 24500, pricePerM2: 1633 },
    ],
  },
  {
    id: 'nagatinskaya',
    name: 'Нагатинская',
    address: 'Нагатинская ул., д. 16, ТЦ «Конфетти»',
    metro: 'Нагатинская',
    prices: [
      { area: 1, price: 3200, pricePerM2: 3200 },
      { area: 2, price: 5700, pricePerM2: 2850 },
      { area: 3, price: 7800, pricePerM2: 2600 },
      { area: 4, price: 9700, pricePerM2: 2425 },
      { area: 6, price: 13600, pricePerM2: 2267 },
      { area: 8, price: 17300, pricePerM2: 2163 },
      { area: 10, price: 20700, pricePerM2: 2070 },
      { area: 15, price: 29000, pricePerM2: 1933 },
    ],
  },
  {
    id: 'kozhuhovskaya',
    name: 'Кожуховская (Мозаика)',
    address: '7-я Кожуховская ул., д. 9, ТРЦ «Мозаика», паркинг 2 уровень',
    metro: 'Кожуховская',
    prices: [
      { area: 1, price: 3400, pricePerM2: 3400 },
      { area: 2, price: 6000, pricePerM2: 3000 },
      { area: 3, price: 8200, pricePerM2: 2733 },
      { area: 4, price: 10200, pricePerM2: 2550 },
      { area: 6, price: 14200, pricePerM2: 2367 },
      { area: 8, price: 18000, pricePerM2: 2250 },
      { area: 10, price: 21600, pricePerM2: 2160 },
      { area: 15, price: 30200, pricePerM2: 2013 },
    ],
  },
  {
    id: 'entuziastov',
    name: 'Шоссе Энтузиастов',
    address: 'Балашиха, мкр-н Никольско-Архангельский, ПСЗ, д. 2а',
    metro: 'Щёлковская',
    prices: [
      { area: 1, price: 2200, pricePerM2: 2200 },
      { area: 2, price: 4000, pricePerM2: 2000 },
      { area: 3, price: 5500, pricePerM2: 1833 },
      { area: 4, price: 6900, pricePerM2: 1725 },
      { area: 6, price: 9700, pricePerM2: 1617 },
      { area: 8, price: 12400, pricePerM2: 1550 },
      { area: 10, price: 14900, pricePerM2: 1490 },
      { area: 15, price: 20900, pricePerM2: 1393 },
    ],
  },
]

export default function PriceTable() {
  const [selectedWarehouseId, setSelectedWarehouseId] = useState('all')

  const visibleWarehouses = selectedWarehouseId === 'all'
    ? WAREHOUSES
    : WAREHOUSES.filter((warehouse) => warehouse.id === selectedWarehouseId)

  return (
    <section className={styles.section}>
      <div className="container">
        <div className={styles.sectionHeading}>
          <p className={styles.eyebrow}>Тарифы</p>
          <h2 className={styles.sectionTitle}>Таблица цен на аренду склада</h2>
          <p className={styles.sectionSubtitle}>
            Цены указаны за месяц, включая НДС. Высота бокса — 3 метра, поэтому можно использовать объём по максимуму.
          </p>
        </div>

        <div className={styles.filters} role="tablist" aria-label="Фильтр по складам">
          <button
            type="button"
            onClick={() => setSelectedWarehouseId('all')}
            className={`${styles.buttonFilter} ${selectedWarehouseId === 'all' ? styles.buttonFilterActive : ''}`}
          >
            Все склады
          </button>
          {WAREHOUSES.map((warehouse) => (
            <button
              key={warehouse.id}
              type="button"
              onClick={() => setSelectedWarehouseId(warehouse.id)}
              className={`${styles.buttonFilter} ${selectedWarehouseId === warehouse.id ? styles.buttonFilterActive : ''}`}
            >
              {warehouse.name}
            </button>
          ))}
        </div>

        <div className={styles.tableList}>
          {visibleWarehouses.map((warehouse) => (
            <article key={warehouse.id} className={`${styles.card} ${styles.tableCard}`}>
              <header className={styles.tableHeader}>
                <div>
                  <h3 className={styles.cardTitle}>Склад на {warehouse.name}</h3>
                  <p className={styles.tableHeaderMeta}>{warehouse.address}</p>
                </div>
                {warehouse.metro ? (
                  <div className={styles.metroBadge}>
                    <span className={styles.metroMark}>М</span>
                    <span>{warehouse.metro}</span>
                  </div>
                ) : null}
              </header>

              <div className={styles.tableWrap}>
                <table className={styles.table}>
                  <thead>
                    <tr>
                      <th>Площадь</th>
                      <th>Объём</th>
                      <th data-align="right">Стоимость / мес</th>
                      <th data-align="right">Цена за м²</th>
                      <th data-align="right">Действие</th>
                    </tr>
                  </thead>
                  <tbody>
                    {warehouse.prices.map((priceRow, index) => (
                      <tr
                        key={priceRow.area}
                        className={`${styles.tableRow} ${index === 0 ? styles.tableRowAccent : ''}`}
                      >
                        <td>
                          <div className={styles.areaCell}>
                            <span className={styles.areaValue}>{priceRow.area} м²</span>
                            {index === 0 ? <span className={styles.minBadge}>Мин.</span> : null}
                          </div>
                        </td>
                        <td className={styles.muted}>{priceRow.area * 3} м³</td>
                        <td data-align="right">
                          <span className={styles.priceValue}>{priceRow.price.toLocaleString('ru-RU')} ₽</span>
                        </td>
                        <td data-align="right" className={styles.muted}>
                          {priceRow.pricePerM2.toLocaleString('ru-RU')} ₽
                        </td>
                        <td data-align="right">
                          <Link href="/rental_catalog/" className={`${styles.buttonPrimary} ${styles.tableAction}`}>
                            Арендовать
                          </Link>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </article>
          ))}
        </div>

        <p className={styles.tableNote}>
          Указанные площади приведены для примера цен и не включают цены по акциям. Актуальная информация о свободных
          боксах обновляется в режиме реального времени на странице <Link href="/online/">онлайн аренды</Link>.
        </p>
      </div>
    </section>
  )
}
