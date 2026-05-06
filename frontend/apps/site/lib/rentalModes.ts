import type { Box } from '@/types/box'
import type { RentalMode } from '@/types/rental'

type RentalModeConfig = {
  mode: RentalMode
  label: string
  itemLabel: string
  pluralLabel: string
  listingLabel: string
  countLabel: string
  path: string
  heroTitle: string
  heroDescription: string
}

type GenericCatalogModeCopy = {
  label: string
  itemLabel: string
  pluralLabel: string
  listingLabel: string
  countLabel: string
  path: string
}

const MODE_CONFIG: Record<RentalMode, RentalModeConfig> = {
  box: {
    mode: 'box',
    label: 'Аренда бокса',
    itemLabel: 'Бокс',
    pluralLabel: 'Боксы',
    listingLabel: 'боксов',
    countLabel: 'свободных боксов',
    path: '/rental_catalog/',
    heroTitle: 'Аренда боксов в Москве',
    heroDescription: 'Выберите удобный склад, а затем посмотрите доступные боксы для хранения вещей и товаров.',
  },
  container: {
    mode: 'container',
    label: 'Аренда контейнера',
    itemLabel: 'Контейнер',
    pluralLabel: 'Контейнеры',
    listingLabel: 'контейнеров',
    countLabel: 'свободных контейнеров',
    path: '/rental_catalog/konteynery/',
    heroTitle: 'Аренда контейнеров в Москве',
    heroDescription: 'Выберите склад, где доступны контейнеры, и подберите подходящий контейнер для хранения.',
  },
  cell: {
    mode: 'cell',
    label: 'Аренда ячейки',
    itemLabel: 'Ячейка',
    pluralLabel: 'Ячейки',
    listingLabel: 'ячеек',
    countLabel: 'свободных ячеек',
    path: '/rental_catalog/yacheyka/',
    heroTitle: 'Аренда ячеек в Москве',
    heroDescription: 'Выберите склад и посмотрите доступные ячейки для компактного хранения вещей.',
  },
  storage: {
    mode: 'storage',
    label: 'Аренда кладовки',
    itemLabel: 'Кладовка',
    pluralLabel: 'Кладовки',
    listingLabel: 'кладовок',
    countLabel: 'свободных кладовок',
    path: '/rental_catalog/arenda_kladovki/',
    heroTitle: 'Аренда кладовок в Москве',
    heroDescription: 'Кладовки представлены антресольными боксами. Выберите склад и подберите подходящую кладовку.',
  },
  room: {
    mode: 'room',
    label: 'Аренда помещения',
    itemLabel: 'Помещение',
    pluralLabel: 'Помещения',
    listingLabel: 'помещений',
    countLabel: 'свободных помещений',
    path: '/rental_catalog/arenda_pomeshcheniya/',
    heroTitle: 'Аренда помещений в Москве',
    heroDescription: 'Выберите склад и посмотрите доступные помещения: боксы, офисы, контейнеры и антресольные боксы.',
  },
}

const SLUG_TO_MODE: Record<string, RentalMode> = {
  '': 'box',
  'konteynery': 'container',
  'yacheyka': 'cell',
  'arenda_kladovki': 'storage',
  'arenda_pomeshcheniya': 'room',
}

const DEFAULT_CATALOG_MODE_COPY: GenericCatalogModeCopy = {
  label: 'Склады',
  itemLabel: 'Помещение',
  pluralLabel: 'Помещения',
  listingLabel: 'помещений',
  countLabel: 'свободных помещений',
  path: '/',
}

export function isRentalMode(value: string | null | undefined): value is RentalMode {
  return value === 'box'
    || value === 'container'
    || value === 'cell'
    || value === 'storage'
    || value === 'room'
}

export function normalizeRentalMode(value: string | null | undefined): RentalMode {
  return isRentalMode(value) ? value : 'box'
}

export function rentalModeFromCatalogSlug(slug?: string[]): RentalMode | null {
  const key = slug?.join('/') ?? ''
  return SLUG_TO_MODE[key] ?? null
}

export function getRentalModeConfig(mode: RentalMode): RentalModeConfig {
  return MODE_CONFIG[mode]
}

export function getCatalogModeCopy(mode?: RentalMode): GenericCatalogModeCopy {
  return mode ? MODE_CONFIG[mode] : DEFAULT_CATALOG_MODE_COPY
}

export function getRentalCatalogPath(mode: RentalMode): string {
  return MODE_CONFIG[mode].path
}

export function inferRentalModeFromBox(box: Pick<Box, 'object_type' | 'rent_type'>): RentalMode {
  if (box.object_type === 'Антресольный бокс') return 'storage'
  if (box.object_type === 'Контейнер') return 'container'
  if (box.object_type === 'Ячейка') return 'cell'
  if (box.rent_type === 'Аренда офиса') return 'room'
  if (box.object_type === 'Бокс') return 'box'
  return 'box'
}
