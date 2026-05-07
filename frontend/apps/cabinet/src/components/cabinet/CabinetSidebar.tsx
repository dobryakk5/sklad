'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import {
  LogOut,
  PhoneCall,
  Mail,
  MessageSquare,
  MapPin,
} from 'lucide-react';
import { logout } from '@alfasklad/api-client';
import { SITE_URL } from '@/lib/navigation';

// Роуты в корне — нет префикса /cabinet
const mainItems = [
  { href: '/',          label: 'Лицевой счет' },
  { href: '/contracts', label: 'Договоры и счета' },
  { href: '/requests',  label: 'Мои обращения' },
  { href: '/profile',   label: 'Личные данные' },
];

const actionItems = [
  { href: '/requests?type=callback', label: 'Заказать звонок',    icon: PhoneCall },
  { href: '/requests?type=question', label: 'Написать сообщение', icon: Mail },
  { href: '/requests?type=review',   label: 'Оставить отзыв',     icon: MessageSquare },
  { href: '/warehouses',             label: 'Ближайший склад',     icon: MapPin },
];

function isActive(pathname: string, href: string) {
  if (href === '/') return pathname === '/';
  return pathname.startsWith(href.split('?')[0]);
}

export function CabinetSidebar() {
  const pathname = usePathname();

  async function handleLogout() {
    try {
      await logout();
    } finally {
      window.location.assign(SITE_URL);
    }
  }

  return (
    <div className="space-y-8">
      <nav className="border border-[#eceff3] bg-white">
        {mainItems.map((item) => {
          const active = isActive(pathname, item.href);
          return (
            <Link
              key={item.href}
              href={item.href}
              className={[
                'block border-b border-[#eceff3] px-6 py-5 text-[15px] transition',
                active
                  ? 'bg-[#f6f7f9] font-semibold text-[#333]'
                  : 'text-[#444] hover:bg-[#fafafa]',
              ].join(' ')}
            >
              {item.label}
            </Link>
          );
        })}

        <button
          onClick={handleLogout}
          className="flex w-full items-center gap-3 px-6 py-5 text-left text-[15px] text-[#444] transition hover:bg-[#fafafa]"
        >
          <LogOut size={15} />
          <span>Выйти</span>
        </button>
      </nav>

      <nav className="border border-[#eceff3] bg-white">
        {actionItems.map((item) => {
          const Icon = item.icon;
          return (
            <Link
              key={item.href}
              href={item.href}
              className="flex items-center gap-6 border-b border-[#eceff3] px-7 py-6 text-[11px] font-medium uppercase tracking-[0.12em] text-[#555] transition last:border-b-0 hover:bg-[#fafafa]"
            >
              <Icon size={20} className="text-[#f45454]" />
              <span>{item.label}</span>
            </Link>
          );
        })}
      </nav>
    </div>
  );
}
