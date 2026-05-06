import { CabinetBreadcrumbs } from '@/components/cabinet/CabinetBreadcrumbs';
import { CabinetErrorState } from '@/components/cabinet/CabinetErrorState';
import { CabinetPageTitle } from '@/components/cabinet/CabinetPageTitle';
import { WarehousesClient } from '@/components/cabinet/warehouses/WarehousesClient';
import { getCabinetWarehouses } from '@/lib/warehousesApi';

export default async function WarehousesPage() {
  try {
    const warehouses = await getCabinetWarehouses();

    return (
      <>
        <CabinetPageTitle
          title="Ближайший склад"
          breadcrumbs={
            <CabinetBreadcrumbs
              items={[{ label: 'Ближайший склад' }]}
            />
          }
        />

        <WarehousesClient warehouses={warehouses} />
      </>
    );
  } catch (error) {
    return (
      <>
        <CabinetPageTitle
          title="Ближайший склад"
          breadcrumbs={
            <CabinetBreadcrumbs
              items={[{ label: 'Ближайший склад' }]}
            />
          }
        />

        <CabinetErrorState
          description={
            error instanceof Error
              ? error.message
              : 'Не удалось загрузить список складов.'
          }
        />
      </>
    );
  }
}
