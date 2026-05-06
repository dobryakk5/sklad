import type { Metadata } from 'next'
import localFont from 'next/font/local'
import SiteChrome from '@/components/SiteChrome'
import './globals.css'
import './catalog.css'
import './box-detail.css'
import './cabinet.css'
import './reviews.css'

export const metadata: Metadata = {
  title: {
    default: 'Аренда боксов, контейнеров и кладовок в Москве — АльфаСклад',
    template: '%s — АльфаСклад',
  },
  description:
    'Боксы, контейнеры, ячейки, кладовки и помещения для хранения вещей в Москве. Доступ 24/7, охрана и удобный выбор склада онлайн.',
}

const geologica = localFont({
  src: [
    {
      path: './fonts/Geologica-Variable.ttf',
      weight: '100 900',
      style: 'normal',
    },
  ],
  variable: '--font-geologica',
  display: 'swap',
})

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="ru" className={geologica.variable}>
      <body>
        <SiteChrome>{children}</SiteChrome>
      </body>
    </html>
  )
}
