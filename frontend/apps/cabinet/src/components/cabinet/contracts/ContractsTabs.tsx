'use client';

import { useState } from 'react';
import type { ApiContract, ApiInvoice } from '@alfasklad/api-client';

type ContractsTabsProps = {
  contracts: ApiContract[];
  invoices: ApiInvoice[];
};

type TabKey = 'contracts' | 'invoices';

function formatMoney(amount: number, currency = 'RUB') {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency,
    maximumFractionDigits: 2,
  }).format(amount);
}

export function ContractsTabs({
  contracts,
  invoices,
}: ContractsTabsProps) {
  const [activeTab, setActiveTab] = useState<TabKey>('contracts');

  return (
    <section className="rounded-[2px] border border-[#e6eaf0] bg-white shadow-[0_8px_24px_rgba(15,23,42,0.04)]">
      <div className="flex gap-2 border-b border-[#eef2f6] px-5 py-4 sm:px-6">
        {[
          { key: 'contracts' as const, label: `Договоры (${contracts.length})` },
          { key: 'invoices' as const, label: `Счета (${invoices.length})` },
        ].map((tab) => (
          <button
            key={tab.key}
            type="button"
            onClick={() => setActiveTab(tab.key)}
            className={[
              'rounded-full border px-4 py-2 text-[13px] font-medium transition',
              activeTab === tab.key
                ? 'border-[#f45454] bg-[#fff1f1] text-[#c53c3c]'
                : 'border-[#e6eaf0] text-[#4a5565]',
            ].join(' ')}
          >
            {tab.label}
          </button>
        ))}
      </div>

      {activeTab === 'contracts' ? (
        <div className="overflow-x-auto">
          <table className="min-w-full border-collapse">
            <thead>
              <tr className="border-b border-[#eef2f6] text-left text-[12px] uppercase tracking-[0.08em] text-[#8a93a1]">
                <th className="px-5 py-4 sm:px-6">Договор</th>
                <th className="px-5 py-4 sm:px-6">Статус</th>
                <th className="px-5 py-4 sm:px-6">Бокс</th>
                <th className="px-5 py-4 sm:px-6">Баланс</th>
              </tr>
            </thead>
            <tbody>
              {contracts.map((contract) => (
                <tr key={contract.id} className="border-b border-[#f3f5f8] text-[15px] text-[#273142] last:border-b-0">
                  <td className="px-5 py-4 sm:px-6">{contract.number}</td>
                  <td className="px-5 py-4 capitalize sm:px-6">{contract.status}</td>
                  <td className="px-5 py-4 sm:px-6">{contract.box_name ?? '—'}</td>
                  <td className="px-5 py-4 sm:px-6">{formatMoney(contract.balance)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ) : (
        <div className="overflow-x-auto">
          <table className="min-w-full border-collapse">
            <thead>
              <tr className="border-b border-[#eef2f6] text-left text-[12px] uppercase tracking-[0.08em] text-[#8a93a1]">
                <th className="px-5 py-4 sm:px-6">Счет</th>
                <th className="px-5 py-4 sm:px-6">Договор</th>
                <th className="px-5 py-4 sm:px-6">Статус</th>
                <th className="px-5 py-4 sm:px-6">Сумма</th>
              </tr>
            </thead>
            <tbody>
              {invoices.map((invoice) => (
                <tr key={invoice.id} className="border-b border-[#f3f5f8] text-[15px] text-[#273142] last:border-b-0">
                  <td className="px-5 py-4 sm:px-6">{invoice.number}</td>
                  <td className="px-5 py-4 sm:px-6">{invoice.contract_number}</td>
                  <td className="px-5 py-4 sm:px-6">{invoice.status}</td>
                  <td className="px-5 py-4 sm:px-6">
                    {formatMoney(invoice.amount, invoice.currency)}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </section>
  );
}
