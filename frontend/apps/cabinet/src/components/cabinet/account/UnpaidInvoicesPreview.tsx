'use client';

import type { ApiInvoice, InvoiceStatus } from '@alfasklad/api-client';

type UnpaidInvoicesPreviewProps = {
  invoices: ApiInvoice[];
};

function formatMoney(amount: number, currency: string) {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency,
    maximumFractionDigits: 2,
  }).format(amount);
}

const invoiceStatusLabels: Record<InvoiceStatus, string> = {
  not_paid: 'Не оплачен',
  processing: 'В обработке',
  partial: 'Частично оплачен',
  paid: 'Оплачен',
  cancelled: 'Отменен',
};

export function UnpaidInvoicesPreview({
  invoices,
}: UnpaidInvoicesPreviewProps) {
  return (
    <div className="px-5 py-6 sm:px-6">
      <div className="mb-4 flex items-center justify-between gap-4">
        <h2 className="text-[20px] font-medium text-[#273142]">
          Неоплаченные счета
        </h2>
        <span className="text-[13px] text-[#8a93a1]">
          {invoices.length} шт.
        </span>
      </div>

      {invoices.length === 0 ? (
        <p className="text-[15px] text-[#667085]">Неоплаченных счетов нет.</p>
      ) : (
        <div className="space-y-3">
          {invoices.map((invoice) => (
            <div
              key={invoice.id}
              className="flex flex-col gap-3 rounded-[2px] border border-[#eef2f6] px-4 py-4 sm:flex-row sm:items-center sm:justify-between"
            >
              <div>
                <div className="text-[16px] font-medium text-[#273142]">
                  Счет {invoice.number}
                </div>
                <div className="mt-1 text-[14px] text-[#667085]">
                  Договор {invoice.contract_number} • {invoiceStatusLabels[invoice.status]}
                </div>
              </div>

              <div className="flex items-center gap-3">
                <div className="text-[16px] font-medium text-[#273142]">
                  {formatMoney(invoice.amount, invoice.currency)}
                </div>
                <button
                  type="button"
                  disabled
                  className="rounded-[2px] border border-[#d8dce2] bg-[#f5f7fa] px-4 py-3 text-[13px] font-semibold uppercase tracking-[0.06em] text-[#a0a8b5]"
                >
                  Только просмотр
                </button>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
