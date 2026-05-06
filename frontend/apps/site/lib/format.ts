export function formatNumberRu(value: number | null | undefined): string | null {
  return value == null ? null : value.toLocaleString('ru-RU')
}
