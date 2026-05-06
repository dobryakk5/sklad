// app/online/page.tsx
// Server Component — грузит склады на сервере, передаёт в клиентский wizard.
import type { Metadata } from 'next'
import { getWarehouses } from '@/lib/api'
import OnlineWizard from '@/components/online/OnlineWizard'
import OnlineHeader from '@/components/online/OnlineHeader'
import OnlineFooter from '@/components/online/OnlineFooter'

export const metadata: Metadata = {
  title: 'Аренда бокса для хранения вещей онлайн',
}

export const dynamic = 'force-dynamic'

export default async function OnlinePage() {
  const warehouses = await getWarehouses()

  return (
    <div className="online-page">
      <OnlineHeader />

      <main className="online-page__main">
        <section className="online-page-top">
          <div className="online-maxwidth">
            <div className="online-breadcrumb">
              <a href="https://alfasklad.ru/online/">Главная</a>
            </div>
            <h1 className="online-page-title">Аренда бокса для хранения вещей онлайн</h1>
          </div>
        </section>

        <div className="online-maxwidth">
          <OnlineWizard warehouses={warehouses} />
        </div>
      </main>

      <OnlineFooter />
    </div>
  )
}
