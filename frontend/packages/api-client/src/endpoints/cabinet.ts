import { apiFetch, type FetchConfig } from '../client';
import type {
  ApiContract,
  ApiInvoice,
  ApiInvoiceDetail,
  ApiBalance,
  ApiPaymentMethod,
  ApiPaymentResult,
  ApiAutopayEnableResult,
  ApiCabinetRequestPayload,
  ApiCabinetRequestResult,
} from '../types';

// ------------------------------------------------------------------ //
//  Договоры                                                            //
// ------------------------------------------------------------------ //

export async function getContracts(
  config: FetchConfig = {},
): Promise<{ data: ApiContract[] }> {
  return apiFetch('/cabinet/contracts', config);
}

// ------------------------------------------------------------------ //
//  Счета                                                               //
// ------------------------------------------------------------------ //

export async function getInvoices(
  config: FetchConfig = {},
): Promise<{ data: ApiInvoice[] }> {
  return apiFetch('/cabinet/invoices', config);
}

export async function getInvoice(
  id: number,
  config: FetchConfig = {},
): Promise<ApiInvoiceDetail> {
  return apiFetch(`/cabinet/invoices/${id}`, config);
}

// ------------------------------------------------------------------ //
//  Баланс                                                              //
// ------------------------------------------------------------------ //

export async function getBalance(
  config: FetchConfig = {},
): Promise<ApiBalance> {
  return apiFetch('/cabinet/balance', config);
}

// ------------------------------------------------------------------ //
//  Платёжный метод                                                     //
// ------------------------------------------------------------------ //

export async function getPaymentMethod(
  config: FetchConfig = {},
): Promise<{ data: ApiPaymentMethod | null }> {
  return apiFetch('/cabinet/payment-method', config);
}

// ------------------------------------------------------------------ //
//  Оплата                                                              //
// ------------------------------------------------------------------ //

export async function payInvoice(
  invoiceId: number,
): Promise<ApiPaymentResult> {
  return apiFetch(`/cabinet/invoices/${invoiceId}/pay`, { method: 'POST' });
}

export async function topUpBalance(
  contractId: number,
  amount: number,
): Promise<ApiPaymentResult> {
  return apiFetch('/cabinet/balance/top-up', {
    method: 'POST',
    body: JSON.stringify({ contract_id: contractId, amount }),
  });
}

// ------------------------------------------------------------------ //
//  Автоплатёж                                                          //
// ------------------------------------------------------------------ //

export async function enableAutopay(): Promise<ApiAutopayEnableResult> {
  return apiFetch('/cabinet/autopay/enable', { method: 'POST' });
}

export async function disableAutopay(): Promise<void> {
  await apiFetch('/cabinet/autopay/disable', { method: 'POST' });
}

export async function unlinkPaymentMethod(): Promise<void> {
  await apiFetch('/cabinet/payment-method/unlink', { method: 'POST' });
}

// ------------------------------------------------------------------ //
//  Обращения                                                           //
// ------------------------------------------------------------------ //

export async function createCabinetRequest(
  payload: ApiCabinetRequestPayload,
): Promise<ApiCabinetRequestResult> {
  return apiFetch('/cabinet/requests', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}
