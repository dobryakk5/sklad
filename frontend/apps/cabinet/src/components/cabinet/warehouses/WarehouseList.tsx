import type { CabinetWarehouse } from '@/lib/warehousesApi';
import { WarehouseCard } from './WarehouseCard';

type WarehouseListProps = {
  warehouses: CabinetWarehouse[];
  selectedId: number | null;
  onSelect: (warehouseId: number) => void;
};

export function WarehouseList({
  warehouses,
  selectedId,
  onSelect,
}: WarehouseListProps) {
  if (warehouses.length === 0) {
    return (
      <div className="border border-[#eceff3] bg-white p-6 text-[15px] text-[#777]">
        По вашему запросу склады не найдены.
      </div>
    );
  }

  return (
    <div className="max-h-[620px] space-y-4 overflow-y-auto pr-2">
      {warehouses.map((warehouse) => (
        <WarehouseCard
          key={warehouse.id}
          warehouse={warehouse}
          active={warehouse.id === selectedId}
          onSelect={() => onSelect(warehouse.id)}
        />
      ))}
    </div>
  );
}
