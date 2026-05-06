import type { ReactNode } from 'react';
import type { ApiUser } from '@alfasklad/api-client';
import { CabinetSidebar } from './CabinetSidebar';
import { CabinetMobileNav } from './CabinetMobileNav';

type CabinetShellProps = {
  user: ApiUser;
  children: ReactNode;
};

export function CabinetShell({ user, children }: CabinetShellProps) {
  return (
    <div className="min-h-screen bg-white text-[#333]">
      <div className="mx-auto grid max-w-[1240px] grid-cols-1 gap-6 px-4 py-5 sm:px-5 md:py-8 lg:grid-cols-[274px_minmax(0,1fr)] lg:gap-10 lg:px-8">
        <aside className="hidden lg:block print:hidden">
          <CabinetSidebar />
        </aside>

        <main className="min-w-0">
          <div className="print:hidden">
            <CabinetMobileNav user={user} />
          </div>

          <div className="pb-12">
            {children}
          </div>
        </main>
      </div>
    </div>
  );
}
