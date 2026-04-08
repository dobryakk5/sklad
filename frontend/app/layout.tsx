import type { Metadata } from 'next'
import { Geologica } from 'next/font/google'
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

const geologica = Geologica({
  subsets: ['latin', 'cyrillic'],
  weight: ['300', '400', '500', '600'],
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
