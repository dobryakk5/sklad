import { cookies } from 'next/headers';
import { getMe } from '@alfasklad/api-client';
import { CabinetBreadcrumbs } from '@/components/cabinet/CabinetBreadcrumbs';
import { CabinetPageTitle } from '@/components/cabinet/CabinetPageTitle';
import { RequestGrid } from '@/components/cabinet/requests/RequestGrid';
import { fetchProtected } from '@/lib/protectedApi';

type RequestsPageProps = {
  searchParams?: Promise<{
    type?: string;
  }>;
};

export default async function RequestsPage({
  searchParams,
}: RequestsPageProps) {
  const cookieStore = await cookies();
  const user = await fetchProtected(getMe({ cookieHeader: cookieStore.toString() }));
  const resolvedSearchParams = await searchParams;

  return (
    <>
      <CabinetPageTitle
        title="Мои обращения"
        breadcrumbs={
          <CabinetBreadcrumbs
            items={[{ label: 'Мои обращения' }]}
          />
        }
      />

      <RequestGrid
        initialType={resolvedSearchParams?.type}
        contactDefaults={{
          name: user.name,
          email: user.email,
          phone: user.phone,
        }}
      />
    </>
  );
}
