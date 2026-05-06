import { cookies } from 'next/headers';
import { getContracts, getInvoices } from '@alfasklad/api-client';
import { CabinetBreadcrumbs } from '@/components/cabinet/CabinetBreadcrumbs';
import { CabinetPageTitle } from '@/components/cabinet/CabinetPageTitle';
import { ContractsTabs } from '@/components/cabinet/contracts/ContractsTabs';
import { fetchProtected } from '@/lib/protectedApi';

export default async function ContractsPage() {
  const cookieStore = await cookies();
  const cfg = { cookieHeader: cookieStore.toString() };

  const [contractsRes, invoicesRes] = await fetchProtected(Promise.all([
    getContracts(cfg),
    getInvoices(cfg),
  ]));

  return (
    <>
      <CabinetPageTitle
        title="Договоры"
        breadcrumbs={
          <CabinetBreadcrumbs
            items={[{ label: 'Договоры и счета' }, { label: 'Договоры' }]}
          />
        }
      />

      <ContractsTabs
        contracts={contractsRes.data}
        invoices={invoicesRes.data}
      />
    </>
  );
}
