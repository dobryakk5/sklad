import type { ReactNode } from 'react';
import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';
import { getMe, ApiError } from '@alfasklad/api-client';
import { CabinetShell } from '@/components/cabinet/CabinetShell';

export default async function ProtectedLayout({
  children,
}: {
  children: ReactNode;
}) {
  const cookieStore = await cookies();
  const cookieHeader = cookieStore.toString();

  try {
    const user = await getMe({ cookieHeader });

    return (
      <CabinetShell user={user}>
        {children}
      </CabinetShell>
    );
  } catch (err) {
    // Сессия истекла или невалидна — отправить на логин
    if (err instanceof ApiError && err.isUnauthorized()) {
      redirect('/login');
    }

    if (err instanceof ApiError && err.code === 'NOT_IMPLEMENTED') {
      return (
        <div className="flex min-h-screen items-center justify-center bg-[#f5f7fa] px-4">
          <div className="w-full max-w-[640px] rounded-[2px] border border-[#e6eaf0] bg-white p-8 shadow-[0_8px_24px_rgba(15,23,42,0.04)]">
            <h1 className="text-[28px] font-medium text-[#273142]">
              Личный кабинет в подготовке
            </h1>
            <p className="mt-4 text-[15px] leading-7 text-[#667085]">
              Новый backend для кабинета ещё не подключён к реальным Bitrix-данным.
              Сейчас фронт работает, но API чтения пока не реализован.
            </p>
            <p className="mt-3 text-[15px] leading-7 text-[#667085]">
              Следующий шаг: реализовать read-only endpoint&apos;ы
              <code className="mx-1 rounded bg-[#f5f7fa] px-1.5 py-0.5 text-[13px] text-[#273142]">/api/cabinet/me</code>
              и связанные маршруты на реальной структуре Bitrix MySQL.
            </p>
          </div>
        </div>
      );
    }

    throw err;
  }
}
