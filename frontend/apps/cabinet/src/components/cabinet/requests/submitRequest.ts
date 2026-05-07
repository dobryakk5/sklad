import {
  ApiError,
  createCabinetRequest,
  type CabinetRequestType,
} from '@alfasklad/api-client';

export function collectRequestFields(
  form: HTMLFormElement,
  extraFields: Record<string, string> = {},
): Record<string, string> {
  const fields: Record<string, string> = { ...extraFields };
  const formData = new FormData(form);

  formData.forEach((value, key) => {
    if (typeof value === 'string') {
      fields[key] = value.trim();
    }
  });

  return fields;
}

export async function submitRequest(
  type: CabinetRequestType,
  form: HTMLFormElement,
  extraFields: Record<string, string> = {},
) {
  await createCabinetRequest({
    type,
    fields: collectRequestFields(form, extraFields),
  });
}

export function requestErrorMessage(error: unknown): string {
  if (error instanceof ApiError) {
    if (error.isUnauthorized()) {
      return 'Сессия истекла. Войдите в личный кабинет заново.';
    }

    if (error.code === 'UNKNOWN_REQUEST_TYPE') {
      return 'Этот тип обращения пока не поддерживается.';
    }

    if (error.code === 'BITRIX_ERROR') {
      return 'Сервис обработки обращений недоступен. Попробуйте позже.';
    }
  }

  return 'Не удалось отправить обращение. Попробуйте еще раз.';
}
