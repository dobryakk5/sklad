'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import type { ApiUser } from '@alfasklad/api-client';

const items = [
  { href: '/', label: 'Счет' },
  { href: '/contracts', label: 'Договоры' },
  { href: '/requests', label: 'Обращения' },
  { href: '/warehouses', label: 'Склады' },
  { href: '/profile', label: 'Профиль' },
  { href: '/payment-methods', label: 'Оплата' },
];

type CabinetMobileNavProps = {
  user: ApiUser;
};

export function CabinetMobileNav({ user }: CabinetMobileNavProps) {
  const pathname = usePathname();

  return (
    <div className="mb-4 rounded-[2px] border border-[#e6eaf0] bg-white p-4 shadow-[0_8px_24px_rgba(15,23,42,0.04)] lg:hidden">
      <div className="mb-3">
        <div className="text-[12px] uppercase tracking-[0.08em] text-[#8a93a1]">
          Личный кабинет
        </div>
        <div className="mt-1 text-[18px] font-medium text-[#273142]">
          {user.name}
        </div>
      </div>

      <nav className="flex flex-wrap gap-2">
        {items.map((item) => {
          const active = item.href === '/' ? pathname === '/' : pathname.startsWith(item.href);
          return (
            <Link
              key={item.href}
              href={item.href}
              className={[
                'rounded-full border px-3 py-2 text-[13px] transition',
                active
                  ? 'border-[#f45454] bg-[#fff1f1] text-[#c53c3c]'
                  : 'border-[#e6eaf0] text-[#4a5565]',
              ].join(' ')}
            >
              {item.label}
            </Link>
          );
        })}
      </nav>
    </div>
  );
}
