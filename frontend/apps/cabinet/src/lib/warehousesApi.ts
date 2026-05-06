type PublicWarehouse = {
  id: number;
  name: string;
  slug: string;
  district: string;
  address: string;
  phone: string;
  metro: string[];
  access_hours?: string;
  reception_hours: string;
  price_per_sqm: number | null;
  available_boxes_count: number;
  total_boxes_count: number;
  photo_url: string | null;
  photos: string[];
  coords: {
    lat: number;
    lng: number;
  } | null;
  description: string | null;
};

export type CabinetWarehouse = {
  id: number;
  name: string;
  slug: string;
  address: string;
  phone: string;
  metro: string;
  workTime: string;
  accessHours?: string;
  latitude: number | null;
  longitude: number | null;
  availableBoxesCount: number;
  totalBoxesCount: number;
  photoUrl: string | null;
  description: string | null;
};

function getApiBase(): string {
  const base = process.env.API_BASE_URL ?? 'http://localhost:8000/api';

  return base.replace(/\/+$/, '');
}

function warehouseToCabinetWarehouse(
  warehouse: PublicWarehouse,
): CabinetWarehouse {
  return {
    id: warehouse.id,
    name: warehouse.name,
    slug: warehouse.slug,
    address: warehouse.address,
    phone: warehouse.phone,
    metro: warehouse.metro.filter(Boolean).join(', '),
    workTime: warehouse.reception_hours || warehouse.access_hours || '—',
    accessHours: warehouse.access_hours,
    latitude: warehouse.coords?.lat ?? null,
    longitude: warehouse.coords?.lng ?? null,
    availableBoxesCount: warehouse.available_boxes_count,
    totalBoxesCount: warehouse.total_boxes_count,
    photoUrl: warehouse.photo_url,
    description: warehouse.description,
  };
}

export async function getCabinetWarehouses(): Promise<CabinetWarehouse[]> {
  const response = await fetch(`${getApiBase()}/warehouses`, {
    next: { revalidate: 60 },
  });

  if (!response.ok) {
    throw new Error(`Warehouse API error ${response.status}`);
  }

  const payload = (await response.json()) as {
    data?: PublicWarehouse[];
  };

  if (!Array.isArray(payload.data)) {
    throw new Error('Warehouse API returned invalid payload');
  }

  return payload.data.map(warehouseToCabinetWarehouse);
}
