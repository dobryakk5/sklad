import type { ApiContract } from '@alfasklad/api-client';

type AccountSummaryProps = {
  balance: number;
  contracts: ApiContract[];
};

function formatMoney(value: number) {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    maximumFractionDigits: 2,
  }).format(value);
}

export function AccountSummary({
  balance,
  contracts,
}: AccountSummaryProps) {
  const activeContracts = contracts.filter((contract) => contract.status === 'active').length;
  const debtContracts = contracts.filter((contract) => contract.balance < 0).length;

  const stats = [
    { label: 'Текущий баланс', value: formatMoney(balance) },
    { label: 'Активных договоров', value: String(activeContracts) },
    { label: 'Договоров с задолженностью', value: String(debtContracts) },
  ];

  return (
    <div className="grid gap-4 border-b border-[#eef2f6] px-5 py-6 sm:grid-cols-3 sm:px-6">
      {stats.map((stat) => (
        <div key={stat.label} className="rounded-[2px] bg-[#f8fafc] px-4 py-4">
          <div className="text-[12px] uppercase tracking-[0.08em] text-[#8a93a1]">
            {stat.label}
          </div>
          <div className="mt-2 text-[24px] font-medium text-[#273142]">
            {stat.value}
          </div>
        </div>
      ))}
    </div>
  );
}
