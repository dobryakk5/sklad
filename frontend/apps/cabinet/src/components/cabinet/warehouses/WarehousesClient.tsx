'use client';

import { useMemo, useState } from 'react';
import type { CabinetWarehouse } from '@/lib/warehousesApi';
import { CabinetEmptyState } from '../CabinetEmptyState';
import { WarehouseList } from './WarehouseList';
import { WarehouseMap } from './WarehouseMap';
import { WarehouseSearch } from './WarehouseSearch';

type WarehousesClientProps = {
  warehouses: CabinetWarehouse[];
};

function matchWarehouse(warehouse: CabinetWarehouse, query: string) {
  const normalizedQuery = query.trim().toLowerCase();

  if (!normalizedQuery) {
    return true;
  }

  const source = [
    warehouse.name,
    warehouse.address,
    warehouse.metro,
    warehouse.phone,
  ]
    .join(' ')
    .toLowerCase();

  return source.includes(normalizedQuery);
}

export function WarehousesClient({ warehouses }: WarehousesClientProps) {
  const [search, setSearch] = useState('');
  const [selectedId, setSelectedId] = useState<number | null>(
    warehouses[0]?.id ?? null,
  );

  const filteredWarehouses = useMemo(
    () => warehouses.filter((warehouse) => matchWarehouse(warehouse, search)),
    [warehouses, search],
  );

  function handleSearchChange(value: string) {
    setSearch(value);

    const nextItems = warehouses.filter((warehouse) =>
      matchWarehouse(warehouse, value),
    );

    setSelectedId(nextItems[0]?.id ?? null);
  }

  return (
    <section className="space-y-6">
      <div className="border border-[#eceff3] bg-white p-5 sm:p-6 md:p-8">
        <h2 className="text-[22px] font-medium text-[#333]">
          Карта складов
        </h2>

        <p className="mt-3 max-w-[760px] text-[15px] leading-7 text-[#777]">
          Склады загружаются из API. Выберите склад из списка или на карте.
          Для расчета маршрута используются Яндекс.Карты.
        </p>

        <div className="mt-6">
          <WarehouseSearch value={search} onChange={handleSearchChange} />
        </div>
      </div>

      {warehouses.length === 0 ? (
        <CabinetEmptyState
          title="Склады не найдены"
          description="Публичный API не вернул доступных складов."
        />
      ) : (
        <div className="grid gap-6 lg:grid-cols-[390px_minmax(0,1fr)]">
          <div className="lg:order-1">
            <WarehouseList
              warehouses={filteredWarehouses}
              selectedId={selectedId}
              onSelect={setSelectedId}
            />
          </div>

          <div className="-order-1 lg:order-2">
            <WarehouseMap
              warehouses={filteredWarehouses}
              selectedId={selectedId}
              onSelect={setSelectedId}
            />
          </div>
        </div>
      )}
    </section>
  );
}
