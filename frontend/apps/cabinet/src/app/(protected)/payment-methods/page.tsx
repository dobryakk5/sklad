import { cookies } from 'next/headers';
import { getPaymentMethod } from '@alfasklad/api-client';
import { CabinetBreadcrumbs } from '@/components/cabinet/CabinetBreadcrumbs';
import { CabinetPageTitle } from '@/components/cabinet/CabinetPageTitle';
import { PaymentMethodsPanel } from '@/components/cabinet/payment/PaymentMethodsPanel';
import { fetchProtected } from '@/lib/protectedApi';

export default async function PaymentMethodsPage() {
  const cookieStore = await cookies();
  const res = await fetchProtected(getPaymentMethod({ cookieHeader: cookieStore.toString() }));

  return (
    <>
      <CabinetPageTitle
        title="Мои платежные средства"
        breadcrumbs={
          <CabinetBreadcrumbs
            items={[{ label: 'Мои платежные средства' }]}
          />
        }
      />

      <PaymentMethodsPanel paymentMethod={res.data} variant="page" />
    </>
  );
}
