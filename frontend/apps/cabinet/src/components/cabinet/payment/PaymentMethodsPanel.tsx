'use client';

import type { ApiPaymentMethod } from '@alfasklad/api-client';
import { AutopayToggle } from './AutopayToggle';

type PaymentMethodsPanelProps = {
  paymentMethod: ApiPaymentMethod | null;
  variant?: 'card' | 'page';
};

export function PaymentMethodsPanel({
  paymentMethod,
  variant = 'card',
}: PaymentMethodsPanelProps) {
  return (
    <section
      className={[
        'rounded-[2px] border border-[#e6eaf0] bg-white shadow-[0_8px_24px_rgba(15,23,42,0.04)]',
        variant === 'page' ? 'px-5 py-6 sm:px-6' : '',
      ].join(' ')}
    >
      <div className={variant === 'card' ? 'px-5 py-6 sm:px-6' : ''}>
        <div className="mb-4 flex items-start justify-between gap-4">
          <div>
            <h2 className="text-[20px] font-medium text-[#273142]">
              Платежное средство
            </h2>
            <p className="mt-1 text-[14px] text-[#667085]">
              Управление привязанной картой и автоплатежом.
            </p>
          </div>

          {paymentMethod?.status === 'active' ? (
            <AutopayToggle checked={paymentMethod.autopay_enabled} />
          ) : null}
        </div>

        {paymentMethod ? (
          <div className="rounded-[2px] border border-[#eef2f6] bg-[#f8fafc] px-4 py-4">
            <div className="text-[12px] uppercase tracking-[0.08em] text-[#8a93a1]">
              Статус
            </div>
            <div className="mt-1 text-[16px] font-medium text-[#273142]">
              {paymentMethod.status === 'active' ? 'Активно' : 'Ожидает подтверждения'}
            </div>

            <div className="mt-4 text-[14px] text-[#667085]">
              {paymentMethod.card_last4
                ? `Карта **** ${paymentMethod.card_last4}`
                : 'Номер карты скрыт платежным провайдером.'}
            </div>

            <button
              type="button"
              disabled
              className="mt-5 rounded-[2px] border border-[#d8dce2] bg-[#f5f7fa] px-4 py-3 text-[13px] font-semibold uppercase tracking-[0.06em] text-[#a0a8b5]"
            >
              Только просмотр
            </button>
          </div>
        ) : (
          <div className="rounded-[2px] border border-dashed border-[#d7dee7] px-4 py-5 text-[15px] text-[#667085]">
            Платежное средство пока не привязано.
          </div>
        )}
      </div>
    </section>
  );
}
