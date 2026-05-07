import { cookies } from 'next/headers';
import {
  getMe,
  getBalance,
  getContracts,
  getInvoices,
  getPaymentMethod,
} from '@alfasklad/api-client';
import { CabinetBreadcrumbs } from '@/components/cabinet/CabinetBreadcrumbs';
import { CabinetCard } from '@/components/cabinet/CabinetCard';
import { CabinetPageTitle } from '@/components/cabinet/CabinetPageTitle';
import { AccountSummary } from '@/components/cabinet/account/AccountSummary';
import { TopUpForm } from '@/components/cabinet/account/TopUpForm';
import { UnpaidInvoicesPreview } from '@/components/cabinet/account/UnpaidInvoicesPreview';
import { PaymentMethodsPanel } from '@/components/cabinet/payment/PaymentMethodsPanel';
import { fetchProtected } from '@/lib/protectedApi';

export default async function DashboardPage() {
  const cookieStore = await cookies();
  const cfg = { cookieHeader: cookieStore.toString() };

  const [user, balance, contractsRes, invoicesRes, paymentMethodRes] =
    await fetchProtected(Promise.all([
      getMe(cfg),
      getBalance(cfg),
      getContracts(cfg),
      getInvoices(cfg),
      getPaymentMethod(cfg),
    ]));

  const unpaidInvoices = invoicesRes.data.filter((inv) =>
    ['not_paid', 'processing', 'partial'].includes(inv.status),
  );

  return (
    <>
      <CabinetPageTitle
        title={user.name}
        breadcrumbs={<CabinetBreadcrumbs items={[]} />}
      />

      <div className="space-y-6">
        <CabinetCard>
          <AccountSummary
            balance={balance.total_balance}
            contracts={contractsRes.data}
          />

          <TopUpForm contracts={contractsRes.data} />

          <UnpaidInvoicesPreview invoices={unpaidInvoices} />
        </CabinetCard>

        <PaymentMethodsPanel paymentMethod={paymentMethodRes.data} />
      </div>
    </>
  );
}
