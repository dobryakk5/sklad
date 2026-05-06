import type { CabinetWarehouse } from '@/lib/warehousesApi';

function hasCoords(warehouse: CabinetWarehouse): boolean {
  return warehouse.latitude !== null && warehouse.longitude !== null;
}

export function buildYandexRouteUrl(warehouse: CabinetWarehouse): string {
  if (hasCoords(warehouse)) {
    const point = `${warehouse.latitude},${warehouse.longitude}`;

    return `https://yandex.ru/maps/?rtext=~${point}&rtt=auto&z=12`;
  }

  const text = encodeURIComponent(warehouse.address || warehouse.name);

  return `https://yandex.ru/maps/?text=${text}`;
}

export function buildYandexPlaceUrl(warehouse: CabinetWarehouse): string {
  const text = encodeURIComponent(warehouse.address || warehouse.name);

  if (hasCoords(warehouse)) {
    return `https://yandex.ru/maps/?text=${text}&ll=${warehouse.longitude}%2C${warehouse.latitude}&z=15`;
  }

  return `https://yandex.ru/maps/?text=${text}`;
}
