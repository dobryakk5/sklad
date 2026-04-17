// app/online/layout.tsx
// Изолирует /online от корневого SiteChrome (хедер/футер этой секции — свои).
import type { Metadata } from 'next'
import './online.css'

export const metadata: Metadata = {
  title: {
    default: 'Аренда бокса онлайн — АльфаСклад',
    template: '%s — АльфаСклад',
  },
  icons: {
    icon: 'https://alfasklad.ru/favicon.ico',
    shortcut: 'https://alfasklad.ru/favicon.ico',
    apple: 'https://alfasklad.ru/upload/CPriority/eb1/s9d02kmndr73347sm89hpgmt5ypoe726/map_logo_w_120x120.png',
  },
}

export default function OnlineLayout({ children }: { children: React.ReactNode }) {
  return (
    <>
      <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet"
      />
      {children}
    </>
  )
}
