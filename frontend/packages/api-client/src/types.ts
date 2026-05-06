// Типы соответствуют API-контрактам из Этапа 0

export type ApiUser = {
  id: number;
  name: string;
  email: string;
  phone: string;
};

export type ContractStatus = 'active' | 'inactive';

export type ApiContract = {
  id: number;
  number: string;
  status: ContractStatus;
  balance: number;       // отрицательный = долг
  box_id: number | null;
  box_name: string | null;
  contract_guid: string;
};

export type InvoiceStatus = 'not_paid' | 'processing' | 'partial' | 'paid';

export type ApiInvoice = {
  id: number;
  number: string;
  status: InvoiceStatus;
  amount: number;
  currency: string;
  contract_id: number | null;
  contract_number: string;
  created_at: string | null;
};

export type ApiInvoiceDetail = ApiInvoice & {
  items: ApiInvoiceItem[];
};

export type ApiInvoiceItem = {
  name: string;
  quantity: number;
  price: number;
};

export type PaymentMethodStatus = 'pending' | 'active';

export type ApiPaymentMethod = {
  id: number;
  status: PaymentMethodStatus;
  autopay_enabled: boolean;
  card_last4: string | null;
};

export type ApiBalance = {
  total_balance: number;
  total_debt: number;
  by_contract: ApiContractBalance[];
};

export type ApiContractBalance = {
  contract_id: number;
  contract_number: string;
  balance: number;
};

export type ApiPaymentResult = {
  order_id: number;
  payment_url: string;
};

export type ApiAutopayEnableResult = {
  confirmation_url: string;
};

// Ошибки

export type ApiErrorCode =
  | 'INVALID_CREDENTIALS'
  | 'UNAUTHENTICATED'
  | 'FORBIDDEN'
  | 'NOT_IMPLEMENTED'
  | 'INVOICE_NOT_FOUND'
  | 'CONTRACT_NOT_FOUND'
  | 'INVOICE_ALREADY_PAID'
  | 'INVOICE_NOT_PAYABLE'
  | 'INVALID_AMOUNT'
  | 'BITRIX_ERROR'
  | 'UNKNOWN';

export class ApiError extends Error {
  constructor(
    public readonly code: ApiErrorCode,
    public readonly httpStatus: number,
  ) {
    super(code);
    this.name = 'ApiError';
  }

  isUnauthorized(): boolean {
    return this.httpStatus === 401;
  }

  isForbidden(): boolean {
    return this.httpStatus === 403;
  }
}
