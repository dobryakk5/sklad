import { Boxes, Clock, MapPin, Phone } from 'lucide-react';
import type { CabinetWarehouse } from '@/lib/warehousesApi';
import { buildYandexPlaceUrl, buildYandexRouteUrl } from '@/lib/yandexMaps';

type WarehouseCardProps = {
  warehouse: CabinetWarehouse;
  active: boolean;
  onSelect: () => void;
};

export function WarehouseCard({
  warehouse,
  active,
  onSelect,
}: WarehouseCardProps) {
  return (
    <article
      className={[
        'border border-[#eceff3] bg-white p-5 transition',
        active ? 'border-[#f45454] shadow-[0_10px_30px_rgba(0,0,0,0.08)]' : '',
      ].join(' ')}
    >
      <button
        type="button"
        onClick={onSelect}
        className="block w-full text-left"
      >
        <h2 className="text-[18px] font-medium leading-6 text-[#333]">
          {warehouse.name}
        </h2>

        <div className="mt-4 space-y-3 text-[14px] leading-6 text-[#777]">
          <div className="flex gap-3">
            <MapPin size={17} className="mt-1 shrink-0 text-[#f45454]" />
            <div>
              <div>{warehouse.address}</div>
              {warehouse.metro ? (
                <div className="text-[#999]">м. {warehouse.metro}</div>
              ) : null}
            </div>
          </div>

          <div className="flex gap-3">
            <Clock size={17} className="mt-1 shrink-0 text-[#f45454]" />
            <div>{warehouse.workTime}</div>
          </div>

          <div className="flex gap-3">
            <Phone size={17} className="mt-1 shrink-0 text-[#f45454]" />
            <div>{warehouse.phone || '—'}</div>
          </div>

          <div className="flex gap-3">
            <Boxes size={17} className="mt-1 shrink-0 text-[#f45454]" />
            <div>
              Свободно боксов: {warehouse.availableBoxesCount} из{' '}
              {warehouse.totalBoxesCount}
            </div>
          </div>
        </div>
      </button>

      <div className="mt-5 flex flex-wrap gap-3">
        <a
          href={buildYandexRouteUrl(warehouse)}
          target="_blank"
          rel="noreferrer"
          className="inline-flex h-[40px] items-center border border-[#f45454] px-4 text-[11px] font-semibold uppercase tracking-[0.08em] text-[#f45454] transition hover:bg-[#f45454] hover:text-white"
        >
          Построить маршрут
        </a>

        <a
          href={buildYandexPlaceUrl(warehouse)}
          target="_blank"
          rel="noreferrer"
          className="inline-flex h-[40px] items-center border border-[#d8dce2] px-4 text-[11px] font-semibold uppercase tracking-[0.08em] text-[#777] transition hover:border-[#999] hover:text-[#333]"
        >
          Открыть в Яндекс.Картах
        </a>
      </div>
    </article>
  );
}
