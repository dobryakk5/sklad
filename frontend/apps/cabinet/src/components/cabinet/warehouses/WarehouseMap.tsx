'use client';

import { useEffect, useMemo, useRef } from 'react';
import type { CabinetWarehouse } from '@/lib/warehousesApi';

type WarehouseMapProps = {
  warehouses: CabinetWarehouse[];
  selectedId: number | null;
  onSelect: (warehouseId: number) => void;
};

const fallbackCenter: [number, number] = [55.751244, 37.618423];

function hasCoords(
  warehouse: CabinetWarehouse,
): warehouse is CabinetWarehouse & { latitude: number; longitude: number } {
  return warehouse.latitude !== null && warehouse.longitude !== null;
}

type YmapsApi = {
  ready: (callback: () => void) => void;
  Map: new (
    element: HTMLElement,
    state: Record<string, unknown>,
    options?: Record<string, unknown>,
  ) => {
    destroy: () => void;
    geoObjects: {
      add: (object: unknown) => void;
    };
  };
  Placemark: new (
    coords: [number, number],
    properties: Record<string, unknown>,
    options?: Record<string, unknown>,
  ) => {
    events: {
      add: (eventName: string, callback: () => void) => void;
    };
  };
};

declare global {
  interface Window {
    ymaps?: YmapsApi;
    __alfaskladYmapsPromise?: Promise<YmapsApi>;
  }
}

function loadYandexMaps(): Promise<YmapsApi> {
  if (typeof window === 'undefined') {
    return Promise.reject(new Error('Yandex Maps can only run in browser'));
  }

  if (window.ymaps) {
    return Promise.resolve(window.ymaps);
  }

  if (window.__alfaskladYmapsPromise) {
    return window.__alfaskladYmapsPromise;
  }

  const apiKey = process.env.NEXT_PUBLIC_YANDEX_MAPS_API_KEY;
  const src = new URL('https://api-maps.yandex.ru/2.1/');
  src.searchParams.set('lang', 'ru_RU');

  if (apiKey) {
    src.searchParams.set('apikey', apiKey);
  }

  window.__alfaskladYmapsPromise = new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = src.toString();
    script.async = true;
    script.onload = () => {
      if (!window.ymaps) {
        reject(new Error('Yandex Maps API did not initialize'));
        return;
      }

      resolve(window.ymaps);
    };
    script.onerror = () => reject(new Error('Cannot load Yandex Maps API'));
    document.head.appendChild(script);
  });

  return window.__alfaskladYmapsPromise;
}

export function WarehouseMap({
  warehouses,
  selectedId,
  onSelect,
}: WarehouseMapProps) {
  const containerRef = useRef<HTMLDivElement | null>(null);
  const warehousesWithCoords = useMemo(
    () => warehouses.filter(hasCoords),
    [warehouses],
  );
  const selectedWarehouse = warehousesWithCoords.find(
    (item) => item.id === selectedId,
  );

  const center = useMemo<[number, number]>(() => {
    if (selectedWarehouse) {
      return [selectedWarehouse.latitude, selectedWarehouse.longitude];
    }

    if (warehousesWithCoords[0]) {
      return [
        warehousesWithCoords[0].latitude,
        warehousesWithCoords[0].longitude,
      ];
    }

    return fallbackCenter;
  }, [selectedWarehouse, warehousesWithCoords]);

  useEffect(() => {
    const element = containerRef.current;

    if (!element || warehousesWithCoords.length === 0) {
      return;
    }

    let map: InstanceType<YmapsApi['Map']> | null = null;
    let cancelled = false;

    loadYandexMaps()
      .then((ymaps) => {
        ymaps.ready(() => {
          if (cancelled || !element) {
            return;
          }

          map = new ymaps.Map(
            element,
            {
              center,
              zoom: selectedWarehouse ? 13 : 10,
              controls: ['zoomControl', 'fullscreenControl'],
            },
            {
              suppressMapOpenBlock: true,
            },
          );

          warehousesWithCoords.forEach((warehouse) => {
            const placemark = new ymaps.Placemark(
              [warehouse.latitude, warehouse.longitude],
              {
                balloonContentHeader: warehouse.name,
                balloonContentBody: `${warehouse.address}<br/>${warehouse.workTime}`,
                hintContent: warehouse.name,
              },
              {
                preset:
                  warehouse.id === selectedId
                    ? 'islands#redIcon'
                    : 'islands#blueIcon',
              },
            );

            placemark.events.add('click', () => onSelect(warehouse.id));
            map?.geoObjects.add(placemark);
          });
        });
      })
      .catch(() => {
        // Fallback текст рендерится ниже.
      });

    return () => {
      cancelled = true;
      map?.destroy();
    };
  }, [center, onSelect, selectedId, selectedWarehouse, warehousesWithCoords]);

  if (warehousesWithCoords.length === 0) {
    return (
      <div className="flex h-[620px] items-center justify-center border border-[#eceff3] bg-[#f6f7f9] p-8 text-center text-[15px] leading-7 text-[#777] max-lg:h-[420px]">
        API вернул склады без координат. Список складов доступен слева, а карта
        появится после заполнения <code>coords.lat</code> и <code>coords.lng</code>.
      </div>
    );
  }

  return (
    <div className="h-[620px] overflow-hidden border border-[#eceff3] bg-[#f6f7f9] max-lg:h-[420px]">
      <div ref={containerRef} className="h-full w-full" />
    </div>
  );
}
