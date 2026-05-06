'use client';

import type { ChangeEvent } from 'react';
import { useMemo, useState } from 'react';
import type { ApiContract } from '@alfasklad/api-client';

type TopUpFormProps = {
  contracts: ApiContract[];
};

export function TopUpForm({ contracts }: TopUpFormProps) {
  const activeContracts = contracts.filter((c) => c.status === 'active');

  const [contractId, setContractId] = useState<number>(
    activeContracts[0]?.id ?? 0,
  );
  const [amount, setAmount] = useState<string>('');

  const amountNumber = useMemo(() => {
    const parsed = parseFloat(amount.replace(',', '.'));
    return Number.isFinite(parsed) ? parsed : 0;
  }, [amount]);

  if (activeContracts.length === 0) return null;

  return (
    <div className="px-5 py-6 sm:px-6 md:px-10 md:py-8">
      <h2 className="text-[20px] font-medium text-[#333] md:text-[22px]">
        Пополнение лицевого счета
      </h2>

      <p className="mt-3 max-w-[780px] text-[14px] leading-6 text-[#667085]">
        Раздел работает в режиме просмотра. Пополнение счета через новый кабинет
        временно отключено до запуска платёжного backend-потока.
      </p>

      {activeContracts.length > 1 && (
        <div className="mt-6 max-w-[320px]">
          <label className="mb-2 block text-[15px] text-[#777]" htmlFor="contractId">
            Договор
          </label>
          <select
            id="contractId"
            value={contractId}
            onChange={(e) => setContractId(Number(e.target.value))}
            disabled
            className="h-10 w-full border border-[#d8dce2] bg-[#f5f7fa] px-2 text-[15px] text-[#667085] outline-none"
          >
            {activeContracts.map((c) => (
              <option key={c.id} value={c.id}>
                {c.number}{c.box_name ? ` — ${c.box_name}` : ''}
              </option>
            ))}
          </select>
        </div>
      )}

      <div className="mt-6 max-w-[220px] max-sm:max-w-full">
        <label className="mb-2 block text-[15px] text-[#777]" htmlFor="amount">
          Введите сумму
        </label>
        <input
          id="amount"
          inputMode="decimal"
          value={amount}
          onChange={(e: ChangeEvent<HTMLInputElement>) => setAmount(e.target.value)}
          disabled
          className="h-10 w-full border border-[#d8dce2] bg-[#f5f7fa] px-2 text-[15px] text-[#667085] outline-none"
        />
      </div>

      <button
        type="submit"
        disabled
        className="mt-8 h-[52px] min-w-[162px] border border-[#d8dce2] bg-[#f5f7fa] px-8 text-[13px] font-semibold uppercase tracking-[0.08em] text-[#a0a8b5] max-sm:w-full"
      >
        Пополнить
      </button>

      <p className="mt-7 max-w-[780px] text-[15px] leading-7 text-[#777] md:text-[16px]">
        Сейчас в кабинете доступны только просмотр договоров, счетов и
        платёжных реквизитов. Операции с деньгами будут включены отдельно.
      </p>
    </div>
  );
}
